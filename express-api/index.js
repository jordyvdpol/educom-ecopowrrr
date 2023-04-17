console.log("I am running...")

import express from "express"
const app = express()

const courses = [
    {id: 1, name: 'JavaScript'},
    {id: 2, name: 'NodeJS'},
    {id: 3, name: 'Express'}
]

//First route
app.get('/', (request, response) => {
    response.send({page: 'Homepage'})
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

// Server is listening on tcp-port 3000
app.listen(3000, () => {
    console.log("Running!")
})
