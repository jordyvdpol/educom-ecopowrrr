import {MongoClient, ObjectId} from "mongodb"
const mongoUrl = "mongodb://127.0.0.1:27017/"
const mongodb = 'dummyDataApparaat'
const client = new MongoClient(mongoUrl)

export default class uitlezenApparaat {

    static async uitlezenDummyData (status, klantId) {
        const collection = 'dummyData'
        const query = status && klantId ? {status: status, klantId: klantId} : {}
        console.log(`Bezig met ophalen van dummy data van klant ${klantId}`)
        console.log(`${status}`)
        try {
            await client.connect()
            const data = await client.db(mongodb)
                                    .collection(collection)
                                    .find(query)
                                    .toArray()
            if (data.length > 0) {
                console.log(`Historische dummy data voor klant ${klantId} gevonden`)
                return data
            } else {
                console.log(`Geen historische dummy data voor klant ${klantId} gevonden met status ${status}`)
                return data
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }
}

