import klant from './lib/backend_classes.js'
import apparaten from './lib/apparaten.js'
import status from './lib/klanten.js'
import express from "express"

const app = express()




const port = 3000

app.listen(port, () => {
    console.log(`App draait op http://localhost:${port}`)
})



// Function to handle the POST request for registering a customer
app.post('/registreerKlant', klant.registreerKlanten);



// update klant status in klanten database
app.put('/updateKlantStatus', (request, response) => {
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    status.activeerKlantStatus(postcode, huisnummer)
        .then(result => response.send(`${result.modifiedCount} klant status geupdate`))
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





