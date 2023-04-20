import klant from './lib/server_classes.js'
import express from "express"
const app = express()
import klantgegevens from './lib/postcode_tech.js'



const port = 3000

app.listen(port, () => {
    console.log(`App draait op http://localhost:${port}`)
})



app.post('/registreerKlant', (request, response) => {
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    console.log(`postcode: ${postcode}`)
    console.log(`huisnummer: ${huisnummer}`)
    klantgegevens.ophalenKlantgegevens(postcode, huisnummer)
        .then(klantgegevens => {
            klant.opslaanKlantgegevens('klantgegevens', klantgegevens)
                .then(insertedID => response.send(`Inserted course with ID: ${insertedID}`))
                .catch(err => response.status(500).send(err.message))
        })
        .catch(error => {
            console.error('Error fetching data:', error)
            response.status(500).send(error.message)
        });
});


