import { MongoClient, ObjectId } from "mongodb"
import status from './klanten.js'
import postcodeTech from './postcode_tech.js'
import fetch from 'node-fetch'

const mongoUrl = "mongodb://127.0.0.1:27017"
const mongoDBBackend = "backend" // database naam

const client = new MongoClient(mongoUrl)

export default class klant {

    static opslaanKlantgegevens = async  (collection, document) => {
        const postcode = document.postcode
        const huisnummer = document.number
        const klantIdPromise = klant.haalKlantIdOpBackend (postcode, huisnummer)
        const klantIdObject = await klantIdPromise
        if (klantIdObject === null){ 
            document.number = String(document.number);
            try {
                await client.connect()
                const data = await client.db(mongoDBBackend)
                                        .collection(collection)
                                        .insertOne(document)
                return data.insertedId
            } catch (err) {
                throw new Error(err.message)
            } finally {
                await client.close()
            }
        } else {
            throw new Error ('Klant is al aanwezig in de database')
        }
    }


    static registreerKlanten = async (request, response) => {
        try {
            const postcode = request.query.postcode
            const huisnummer = request.query.huisnummer
        
            const insertedDummyCustomerId = await klant.maakDummyKlantFunctie(postcode, huisnummer)
            console.log(`Inserted dummy Klant with id ${insertedDummyCustomerId}`)
        
            const klantgegevens = await ophalenKlantgegevensFunctie(postcode, huisnummer)
            const insertedKlantgegevensId = await opslaanKlantgegevensFunctie(klantgegevens)
        
            await updateKlantStatus(postcode, huisnummer)
            console.log (`klant status geupdate naar actief`)
        
            response.send(`Success`)
          } catch(err) {
            console.error(err); // log the error for debugging purposes
          
            if (err.message === 'Klant is al aanwezig in de database') {
              response.status(409).send('Klant is al aanwezig in de database')
            } else {
              response.status(500).send(err.message);
            }
          }
    }


    static async haalKlantIdOpBackend(postcode, huisnummer) {
        const collection = 'klantgegevens'
        const query = postcode && huisnummer ? {postcode: String(postcode), huisnummer: String(huisnummer)} : {}
        console.log(`postcode: ${postcode} and huisnummer: ${huisnummer}`)
        try {
            await client.connect()
            const data = await client.db(mongoDBBackend)
                .collection(collection)
                .find(query)
                .toArray()
            if (data.length > 0) {
                console.log(`Found customer with _id: ${data[0]._id}`)
                return data[0]._id
            } else {
                console.log(`No customer found for query: ${JSON.stringify(query)} in database ${mongoDBBackend} and collection: ${collection}`)
                return null
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }


    

    // maak dummy klant met inactieve status in the klanten database
    static maakDummyKlantFunctie = async (postcode, huisnummer) => {
        return status.maakDummyKlant(postcode, huisnummer)
    }    
}


  // Function to retrieve customer data from postcode.tech website
  async function ophalenKlantgegevensFunctie(postcode, huisnummer) {
    return postcodeTech.ophalenKlantgegevens(postcode, huisnummer)
  }
  
  // Function to store customer data in the backend database
  async function opslaanKlantgegevensFunctie(klantgegevens) {
    return klant.opslaanKlantgegevens('klantgegevens', klantgegevens)
  }
  
  // Function to update the customer status in the customer database
  async function updateKlantStatus(postcode, huisnummer) {
    try {
      const response = await fetch(`http://localhost:3000/updateKlantStatus?postcode=${postcode}&huisnummer=${huisnummer}`, {
        method: 'PUT'
      });
      if (!response.ok) {
        throw new Error('Failed to update customer status')
      }
      return response;
    } catch (error) {
      console.error('Error updating customer status:', error)
      throw error
    }
  }