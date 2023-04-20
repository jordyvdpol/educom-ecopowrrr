import { MongoClient, ObjectId } from "mongodb"

const mongoUrl = "mongodb://localhost:27017/"
const mongoDB = "klanten" // database name

const client = new MongoClient(mongoUrl)

export default class klant {

    static insert = async  (collection, document) => {
        try {
            await client.connect()
            const data = await client.db(mongoDB)
                                     .collection(collection)
                                     .insertOne(document)
            return data.insertedId
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }
    }
    
}