import klant from '../../educom-ecopowrrr/API/lib/backend_classes.js'
import apparaten from '../../educom-ecopowrrr/API/lib/apparaten.js'
import status from '../../educom-ecopowrrr/API/lib/klanten.js'
import express from "express"

const app = express()


const port = 3000

app.listen(port, () => {
    console.log(`App draait op http://localhost:${port}`)
})



// Function to handle the POST request for registering a customer
app.post('/registreerKlant', klant.registreerKlanten);


app.get('/statusKlant', (request, response) =>{
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    status.klantStatus(postcode, huisnummer)
        .then(result => resposne.send(result))
        .catch(err => resposne.status(500).send(err.message))
})


// update klant status in klanten database
app.put('/updateKlantStatus', (request, response) => {
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    status.activeerKlantStatus(postcode, huisnummer)
        .then(result => response.send(`${result.modifiedCount} klant status geupdate`))
        .catch(err => response.status(500).send(err.message))  
})

app.get('/getKlantData', (request, resposne) =>{
    status.getKlantData()
        .then(result => response.send(`Data klanten`) )
        .catch(err => response.status(500).send(err.message))
})



app.post('/maakDummyApparaten', (request, response) => {
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    apparaten.dummyApparaat('dummyApparaten', postcode, huisnummer)
        .then(insertedId => {
            response.send(`Inserted dummy data apparaat with id: ${insertedId}`)
        })
        .catch(err => {
            if (err.message === 'Klant bestaat niet') {
                response.status(404).send('Klant is niet in de database aanwezig')
            } else {
                response.status(500).send(err.message)
            }
        })
})

app.get('/getLastDummyData', (request, response) => {
    const klantid = "64463896d01ee97e06b7fcf4"
    apparaten.getLastDummyData(klantid)
        .then(result => response.send(result) )
        .catch(err => response.status(500).send(err.message))
})

app.get('/getAllDummyData', (request, response) => {
    apparaten.getAllDummyData()
        .then(result => response.send(result) )
        .catch(err => response.status(500).send(err.message))
})