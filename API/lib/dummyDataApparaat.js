import { MongoClient, ObjectId } from "mongodb"
import apparaatActivatie from './apparaatActivatie.js'

const mongoUrl = "mongodb://127.0.0.1:27017/"
const mongodb = 'dummyDataApparaat'

const client = new MongoClient(mongoUrl)

export default class dummyDataApparaat {
    static maakDummyData = async (klantId, aantal, jaar, maand) => {
        const collection = 'dummyData'
        const dataAanwezigPromise = apparaatActivatie.checkDataAanwezig(klantId, jaar, maand)
        const dataAanwezigObject = await dataAanwezigPromise
        console.log(`${dataAanwezigObject}`)
        
        if(dataAanwezigObject === false) {
            const documentPromise = apparaatActivatie.maakDummyData(klantId, aantal, jaar, maand)
            const documentObject = await documentPromise
            console.log(`object: ${documentObject}`)
            try {
                await client.connect()
                const data = await client.db(mongodb)
                                            .collection(collection)
                                            .insertOne(documentObject)
                return 'success'
            } catch (err) {
                throw new Error(err.message)
            } finally {
                await client.close()
            }
        } else {
            if (dataAanwezigObject === true) {
                throw new Error(`Klant ${klantId} is al actief in jaar ${jaar} en maand ${maand}`)
            }

        }
    }
}