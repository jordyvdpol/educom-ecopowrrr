import { MongoClient, ObjectId } from 'mongodb'

const mongoUrl = "mongodb://127.0.0.1:27017"
const mongoDBKlanten = 'klanten' // database naam

const client = new MongoClient(mongoUrl)

export default class status {

    static maakDummyKlant = async (postcode, huisnummer) => {
        const document = {
            'postcode': postcode,
            'huisnummer': huisnummer,
            'status': 'inactief'
        }
        const collection = 'status'
        const klantIdPromise = status.haalKlantIdOpKlantendb (postcode, huisnummer)
        const klantIdObject = await klantIdPromise
        if (klantIdObject === null){ 
            try {
                await client.connect()
                const data = await client.db(mongoDBKlanten)
                                        .collection(collection)
                                        .insertOne(document)
                return data.insertedId
            }catch (err) {
                throw new Error(err.message)
            } finally {
                await client.close()
            }
        } else {
            throw new Error ('Klant is al aanwezig in de database')
        }

    }

    static activeerKlantStatus = async (postcode, huisnummer) => {
        const collection = 'status'
        const query = postcode && huisnummer ? {postcode, huisnummer} : {}
        let newDocument = { $set: {status: "actief"}}
        try {
            await client.connect()
            const data = await client.db(mongoDBKlanten)
                                     .collection(collection)
                                     .updateOne(query, newDocument)
            return data
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }        
    }


    static async haalKlantIdOpKlantendb(postcode, huisnummer) {
        const collection = 'status'
        const query = postcode && huisnummer ? {postcode: String(postcode), huisnummer: String(huisnummer)} : {}
        console.log(`postcode: ${postcode} and huisnummer: ${huisnummer}`)
        try {
            await client.connect()
            const data = await client.db(mongoDBKlanten)
                                     .collection(collection)
                                     .find(query)
                                     .toArray()
            if (data.length > 0) {
                console.log(`Found customer with _id: ${data[0]._id}`)
                return data[0]._id
            } else {
                console.log(`No customer found for query: ${JSON.stringify(query)} in database ${mongoDBKlanten} and collection: ${collection}`)
                return null
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }
    

}


