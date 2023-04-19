import { MongoClient } from "../express-api/node_modules/mongodb"
const mongoUrl = "mongodb://localhost:27017"
const mongoDB = "express"

export default class mongo {

static list = () => new Promise( (resolve, reject) => {

        MongoClient.connect(mongoUrl, (err, client) => {
           
            if(err) reject({ error: err })            
          
            let adminDb = client.db(mongoDB).admin()
            adminDb.listDatabases((err,result) => {
                client.close()
                if(err) reject({ error: err })  
                resolve(result.databases)
            })
          
          })
    })

}
