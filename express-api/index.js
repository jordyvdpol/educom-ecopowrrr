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

app.get('/', (request, response) => {
    /// First call the list function/promise from the mongo class
    mongo.fetch('test')
         .then(result => response.send(result))
         .catch (err => response.send(err))
})

app.get('/:id', (request, response) => {
    mongo.fetch('test', request.params.id)
        .then (result => response.send(result))
        .catch (err => response.send(err))
})


const courses = 
    {
        // '_id': '1',
        "name": "Express Server API",
        "level": "intermediate",
        "technology": ["JavaScript", "NodeJS", "Express", "MongoDB"]
        }



app.post('/courses', (request, response) => {
    mongo.insert('test', courses)
      .then(insertedId => response.send(`Inserted course with ID: ${insertedId}`))
      .catch( err =>  response.status(500).send(err.message))
})