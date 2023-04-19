import { MongoClient, ObjectId } from "mongodb"
const mongoUrl = "mongodb://localhost:27017/"
const mongoDB = "express" //naam database



const client = new MongoClient(mongoUrl)

export default class Mongo {

  static fetch = async(collection, id = null) => {
    const result = []
    const query = id ? { _id: new ObjectId(id) } : {}
    try {
      await client.connect();
      let data = client.db(mongoDB)
                       .collection(collection)
                       .find(query)
      await data.forEach(item => {
        result.push(item)
      })
      await client.close();
    } catch (err) {
      throw new Error(err.message)
    }
    return(result)
  }



}