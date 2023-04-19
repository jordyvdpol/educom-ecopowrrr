import { MongoClient, ObjectId } from "mongodb"

const mongoUrl = "mongodb://localhost:27017/"
const mongoDB = "express" // database name

const client = new MongoClient(mongoUrl)

export default class Mongo {
    static fetch = async (collection, id = null) => {
        const query = id ? { _id: new ObjectId(id) } : {}
        try {
        await client.connect()
        const data = await client.db(mongoDB)
                                 .collection(collection)
                                 .find(query)
                                 .toArray()
        return data
        } catch (err) {
        throw new Error(err.message)
        } finally {
        await client.close()
        }
    }


    static insert = async (collection, document) => {
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

    static update = async (collection, id, document) => {
        let query = { _id : new ObjectId(id) }
        let newDocument = { $set: document }
        try {
            await client.connect()
            const data = await client.db(mongoDB)
                                     .collection(collection)
                                     .updateOne(query, newDocument)
            return data
            
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }
    }

    static delete = async (collection, id) => {
        let query = { _id : new ObjectId(id) }
        try {
            await client.connect()
            const data = await client.db(mongoDB)
                                     .collection(collection)
                                     .deleteOne(query)
            return data
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }
    }

  }





