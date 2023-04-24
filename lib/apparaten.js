import { MongoClient, ObjectId } from "mongodb"
import crypto from 'crypto'
const mongoUrl = "mongodb://127.0.0.1:27017/"
const mongoDBApparaat = "apparaat" // database naam
const mongoDBKlanten = 'klanten'

const client = new MongoClient(mongoUrl)

export default class apparaten {
    static dummyApparaat = async (collection, postcode, huisnummer) => {
        const datum = new Date()
        const maand = datum.getMonth() + 1
        const klantIdPromise = apparaten.klantIdCheckKlantendb(postcode, huisnummer)
        const klantIdObject = await klantIdPromise
        const klantIdString = klantIdObject.toString()
        const dataAanwezigPromise  = apparaten.checkDataAanwezig(klantIdString, maand)
        const dataAanwezigObject = await dataAanwezigPromise
       if (klantIdObject && dataAanwezigObject != null  ){
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
            if (klantIdObject === null ) {
            throw new Error('Klant bestaat niet of is inactief')
            } else {
                throw new Error(`Data is al opgehaald en aanwezig van maand ${maand} voor klant ${klantIdString}`)
            }
        }
    }

    static async checkDataAanwezig(klant_id, maand) {
        const collection = 'dummyApparaten'
        const query = klant_id && maand ? {klant_id, maand} : {}
        console.log(`zoek naar aanwezigheid van klant_id: ${klant_id} met maand ${maand}`)
        try {
            await client.connect()
            const data = await client.db(mongoDBApparaat)
                                     .collection(collection)
                                     .find(query)
                                     .toArray()
            if (data.length > 0) {
                console.log(`Data is al opgehaald en aanwezig van maand ${maand} voor klant: ${klant_id}`)
                return null
            } else {
                console.log(`Nog geen data aanwezig voor maand ${maand} en klant ${klant_id}`)
                return true
            }
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }
    }

    static klantIdCheckKlantendb = async(postcode, huisnummer) => {
        const collection = 'status'
        const status = "actief"
        const query = postcode && huisnummer && status ? {postcode, huisnummer, status} : {}
        console.log(`postcode: ${postcode}, huisnummer: ${huisnummer} en status: ${status}`)
        try {
            await client.connect()
            const data = await client.db(mongoDBKlanten)
                .collection(collection)
                .find(query)
                .toArray()
            if (data.length > 0) {
                console.log(`Klant gevonden met status ${status} en id: ${data[0]._id}`)
                return data[0]._id
            } else {
                console.log(`Geen klant gevonden voor de volgende query: ${JSON.stringify(query)}`)
                return null
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }


    static async haalDummyData (klant_id) {
        const collection = 'dummyApparaten'
        console.log('locatie 1')
        const query = klant_id ? {klant_id} : {}
        console.log(`Bezig met ophalen van alle dummy data van klant ${klant_id}`)
        try {
            await client.connect()
            const data = await client.db(mongoDBApparaat)
                                    .collection(collection)
                                    .find(query)
                                    .toArray()
            if (data.length > 0) {
                console.log(`Historische dummy data voor klant ${klant_id} gevonden`)
                console.log(`${data[0].devices}`)
                return data
            } else {
                console.log(`Geen historische dummy data voor klant ${klant_id} gevonden`)
                return null
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }

    static berekenTotalYield () {
        
    }




    static maakDummyDataApparaat(id, aantal) {
        const datum = new Date()
        const jaar =  datum.getFullYear()
        const maand = datum.getMonth() + 1
        
        const randomString = crypto.randomBytes(5).toString('hex')
        const dummyDataApparaat = {
            "message_id": randomString,
            "klant_id": id,
            "device_status": "actief",
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