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
        const result = await client.db(mongoDB)
                                   .collection(collection)
                                   .insertOne(document)
        return result.insertedId
    } catch (err) {
        throw new Error(err.message)
    } finally {
        await client.close()
    }
  }




}
