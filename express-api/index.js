// at the top of index.js
import mongo from '../lib/mongo.mjs'

// Set up the express module
import express from "express"
const app = express()

// Server is listening on tcp-port 3000
app.listen(3000, () => {
    console.log("Running!")
})

// --------------------------------------------------- GET ---------------------------------------------------------
const courses = [
    {id: 1, name: 'JavaScript'},
    {id: 2, name: 'NodeJS'},
    {id: 3, name: 'Express'}
]

//First route
app.get('/', (request, response) => {
    /// First call the list function/promise from the mongo class
    mongo.list()

    // Wait for the result
    .then(result => {
        //send the result to the client
        response.send(result)
    })

    //Got an error? Catch it, and retunr it to the client
    .catch( err=> {
        response.send(err)
    })
})


//Second route
app.get('/courses', (request, response) => {
    response.send({courses})
})

//Third route
app.get('/courses/:id', (request, response) => {
    let searchId = parseInt(request.params.id)
    let result = courses.filter(course => course.id === searchId)
    response.send(result[0])
})

// ----------------------------------------------- POST ---------------------------------------------------------
//enable JSON input
app.use (express.json())
/// You might get a CORS error when sending a request from localhost (i.e. a React
/// application) So we need to tell our server to accept all requests.
app.use ((request, result, next) => {
    result.setHeader("Access-Control-Allow-Origin", '*')
    result.setHeader('Acces-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
    result.setHeader('Access-Control-Allow-Headers', 'X-Requested-With, content-type')
    next()
})

app.post('/post', (request, response) => {
    console.log(request.body)
    response.send({received: request.body})
})


// ----------------------------------------------- PUT --------------------------------------------------------

app.put('/put/:id', (request, response) => {
    let searchId = parseInt(request.params.id)
    response.send({id: searchId, received: request.body})
})


// ----------------------------------------------- DELETE --------------------------------------------------------
app.delete('/delete/:id', (request, response) => {
    let searchId = parseInt(request.params.id)
    response.send({id: searchId, deleted:true})
})
