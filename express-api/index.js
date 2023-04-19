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

const updateData =    {
    "name": "updated",
    "level": "updated",
    "technology": ["updated", "updated", "updated", "updated"]
    }

app.put ('/updateCourse/:id',(request, response) => {
    mongo.update('test', request.params.id, updateData)
    .then(result => response.send(`Course updated: ${result.modifiedCount} document modified`))
    .catch( err =>  response.status(500).send(err.message))
})

app.delete ('/deleteCourse/:id',(request, response) => {
    mongo.delete('test', request.params.id)
    .then(result => response.send(`Course deleted: ${result.deletedCount} document deleted`))
    .catch( err => response.status(500).send(err.message))
})

app.post('/createDatabase/:dbName', (request, response) => {
    mongo.createDatabase(request.params.dbName)
    .then(result => response.send(result))
    .catch( err => response.status(500).send(err.message))
})

app.post('/createCollection/:dbName/:collectionName', (request, response) => {
    mongo.createCollection(request.params.dbName, request.params.collectionName)
    .then(result => response.send(result))
    .catch( err => response.status(500).send(err.message))
})

app.post('/createDatabase/:dbName', (request, response) => {
    mongo.createDatabase(request.params.dbName)
    .then(result => response.send(result))
    .catch( err => response.status(500).send(err.message))
})

app.delete('/dropDatabase/:dbName', (request, response) => {
    mongo.dropDatabase(request.params.dbName)
    .then(result => response.send(result))
    .catch( err => response.status(500).send(err.message))
})