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

    static createDatabase = async(dbName) => {
        try {
            await client.connect();
            const dbList = await client.db().admin().listDatabases()
            const dbExists = dbList.databases.some(db => db.name === dbName)
            if (dbExists) {
              return `Database ${dbName} already exists.`
            } else {
              await client.db(dbName).createCollection("test")
              return `Database ${dbName} created.`
            }
          } catch (err) {
            throw new Error(err.message)
          } finally {
            await client.close()
        }
    }

    static createCollection = async (dbName, collectionName) => {
        try {
          await client.connect()
          const db = client.db(dbName)
          const dbList = await client.db().admin().listDatabases()
          const dbExists = dbList.databases.some(db => db.name === dbName)
        if(dbExists) {
            await db.createCollection(collectionName)
            return `Collection ${collectionName} created in ${dbName} database.`
        }else {
            await db.createCollection(collectionName)
            return `database ${dbName} created, collection ${collectionName} created in ${dbName} database.`
        }
        } catch (err) {
            throw new Error(err.message)
          } finally {
            await client.close()
        }
      }

      static dropDatabase = async(dbName) => {
        try {
            await client.connect();
            const db = client.db(dbName)
            const dbList = await client.db().admin().listDatabases()
            const dbExists = dbList.databases.some(db => db.name === dbName)
            if (dbExists) {
              await db.dropDatabase()
              return `Database ${dbName} dropped successfully`
            } else {
              return `Database ${dbName} does not exist.`
            }
          } catch (err) {
            throw new Error(err.message)
          } finally {
            await client.close()
        }
 
      }

    static dropCollection = async(dbName, collectionName) => {
        try {
            await client.connect()
            const db = client.db(dbName)
            const collectionExists = await db.listCollections({ name: collectionName }).hasNext()
            const collections = await db.listCollections().toArray()
            const numCollections = collections.length
            if (collectionExists && numCollections === 1) {
              await db.dropCollection(collectionName)
              return `Database ${dbName} dropped together with collection${collectionName}.`
            } else if (collectionExists && numCollections > 1){
                await db.dropCollection(collectionName)
                return `Collection ${collectionName} dropped from ${dbName} database. There are still ${numCollections - 1} collections in the database.`;
            }else {
              return `Collection ${collectionName} does not exist in ${dbName} database.`
            }
          } catch (err) {
            throw new Error(err.message)
          } finally {
            await client.close()
          }
        }
}
