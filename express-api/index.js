// at the top of index.js
import mongo from './mongo.js'

// Set up the express module
import express from "express"
const app = express()

// Server is listening on tcp-port 3000
app.listen(3000, () => {
    console.log("Running!")
})

// --------------------------------------------------- GET ---------------------------------------------------------
const courses = [
    {
        "name": "Express Server API",
        "level": "intermediate",
        "technology": ["JavaScript", "NodeJS", "Express", "MongoDB"]
        }
]


app.get('/', (request, response) => {
    /// First call the list function/promise from the mongo class
    mongo.fetch()
         .then(result => response.send(result))
         .catch (err => response.send(err))
})


