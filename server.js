import klant from './server_classes.js'
import express from "express"
const app = express()
import https from 'https'


const port = 3000

app.listen(port, () => {
    console.log(`App draait op http://localhost:${port}`)
})


app.post('/registreerKlant', (request, response) => {
    const postcode = request.query.postcode
    const huisnummer = request.query.huisnummer
    console.log(`postcode: ${postcode}`)
    console.log(`huisnummer: ${huisnummer}`)
    fetchData(postcode, huisnummer)
        .then(klantgegevens => {
            klant.insert('klantgegevens', klantgegevens)
                .then(insertedID => response.send(`Inserted course with ID: ${insertedID}`))
                .catch(err => response.status(500).send(err.message))
        })
        .catch(error => {
            console.error('Error fetching data:', error)
            response.status(500).send(error.message)
        });
});

function fetchData(postcode, huisnummer) {
    const url = `https://postcode.tech/api/v1/postcode/full?postcode=${postcode}&number=${huisnummer}`;
    const bearerToken = 'e1f29cae-b9b8-4ddd-b3dd-0fd976394914';
    console.log(`url: ${url}`)
  
    const headers = {
      'Authorization': `Bearer ${bearerToken}`
    };
  
    return fetch(url, { headers })
      .then(response => response.json())
      .then(data => {
        console.log(`postcode tech data: ${JSON.stringify(data)}`)
        return data
      })
      .catch(error => {
        console.error('Error fetching data:', error)
        throw error;
      });
}
