
import dummyDataApparaat from './lib/dummyDataApparaat.js'
import uitlezenApparaat from './lib/uitlezenApparaat.js'
import express from "express"
const app = express()
const port = 3000
app.listen(port, () => {
    console.log(`App draait op http://localhost:${port}`)
})


app.post('/activeerKlant', (request, response) => {
    const status = request.query.status
    const klantId = request.query.id
    const aantal = request.query.aantal
    dummyDataApparaat.activeerApparaat(status, klantId, aantal)
        .then(result => response.send(result))
        .catch(err => response.status(500).send(err.message))
})

app.get('/uitlezenDummyData', (request, response) => {
    const status = request.query.status
    const klantId = request.query.id
    uitlezenApparaat.uitlezenDummyData(status, klantId)
        .then(result => response.send(result))
        .catch(err => response.status(500).send(err.message))
})
