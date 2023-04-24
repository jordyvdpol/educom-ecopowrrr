import { MongoClient, ObjectId } from "mongodb"
import crypto from 'crypto'
const mongoUrl = "mongodb://127.0.0.1:27017/"
const mongoDBApparaat = "apparaat" // database naam
const mongoDBBackend = 'backend'

const client = new MongoClient(mongoUrl)

export default class apparaten {
    static dummyApparaat = async (collection, postcode, huisnummer) => {
        const klantIdPromise = apparaten.haalKlantIdOpBackend(postcode, huisnummer)
        const klantIdObject = await klantIdPromise
       if (klantIdObject != null){
            const klantIdString = klantIdObject.toString()
            const document = apparaten.maakDummyDataApparaat(klantIdString, 2)
            try{
                await client.connect()
                const data = await client.db(mongoDBApparaat)
                                        .collection (collection)
                                        .insertOne(document)
                return data.insertedId
            } catch (err) {
                throw new Error(err.message)
            } finally {
                await client.close()
            }
        } else {
            throw new Error('Customer does not exist')
        }
    }

    static haalKlantIdOpBackend = async(postcode, number) => {
        const collection = 'klantgegevens'
        const query = postcode && number ? {postcode, number} : {}
        console.log(`postcode: ${postcode} and huisnummer: ${number}`)
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
                console.log(`No customer found for query: ${JSON.stringify(query)}`)
                return null
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }


   static maakDummyDataApparaat(id, aantal) {
        const datum = new Date()
        const jaar =  datum.getFullYear()
        const maand = datum.getMonth() + 1
        
        const randomString = crypto.randomBytes(5).toString('hex')
        const dummyDataApparaat = {
            "message_id": randomString,
            "klant_id": id,
            "device_status": "inactive",
            "date": datum.toISOString(),
            "jaar": jaar,
            "maand": maand,
            "devices": []
        };

        for (let i = 0; i < aantal; i++) {
            const randomString = crypto.randomBytes(5).toString('hex')
            const maandelijkseOpbrengstZonnepaneel = apparaten.maandelijkseOpbrengstZonnepaneel(maand)

            const device = { 
            "serial_number":  randomString,
            "device_type": "solar",
            "device_total_yield": maandelijkseOpbrengstZonnepaneel,
            "device_month_yield": maandelijkseOpbrengstZonnepaneel,
            "device_total_surpuls": 0,
            "device_month_surplus": 0,
            };
            dummyDataApparaat.devices.push(device);
        }

        return dummyDataApparaat
    }



    static maandelijkseOpbrengstZonnepaneel(maand) {
        const maandelijkseOpbrengstPercentages = {
            "1": 0.03,
            "2": 0.05,
            "3": 0.08,
            "4": 0.12,
            "5": 0.13,
            "6": 0.13,
            "7": 0.13,
            "8": 0.11,
            "9": 0.10,
            "10": 0.07,
            "11": 0.03,
            "12": 0.02,
        }
        const maandelijkseOpbrengstPercentage = maandelijkseOpbrengstPercentages[maand]
        const maandelijkseOpbrengst = maandelijkseOpbrengstPercentage * 310
        const gemiddeldeMaandelijkseOpbrengst =  (Math.floor(Math.random() * ((maandelijkseOpbrengst+3) - (maandelijkseOpbrengst-3) + 1)) + (maandelijkseOpbrengst-3))
        return gemiddeldeMaandelijkseOpbrengst
    }

}