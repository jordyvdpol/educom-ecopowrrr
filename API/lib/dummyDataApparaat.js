import { MongoClient, ObjectId } from "mongodb"
import crypto from 'crypto'
const mongoUrl = "mongodb://127.0.0.1:27017/"
const mongodb = 'dummyDataApparaat'
const client = new MongoClient(mongoUrl)

export default class dummyDataApparaat {
    static activeerApparaat = async (status, klantId, aantal) => {
        const collection = 'dummyData'
        const datum = new Date()
            const maand = datum.getMonth() +2
            const dataAanwezigPromise = dummyDataApparaat.checkDataAanwezig(klantId, maand)
            const dataAanwezigObject = await dataAanwezigPromise
            console.log(`${dataAanwezigObject}`)
            
            if(dataAanwezigObject === false && status === 'actief') {
                const documentPromise = dummyDataApparaat.maakDummyData(klantId, aantal)
                const documentObject = await documentPromise
                console.log(`object: ${documentObject}`)
                try {
                    await client.connect()
                    const data = await client.db(mongodb)
                                             .collection(collection)
                                             .insertOne(documentObject)
                    return 'success'
                } catch (err) {
                    throw new Error(err.message)
                } finally {
                    await client.close()
                }
            } else {
                if (dataAanwezigObject === true) {
                    throw new Error(`Klant ${klantId} is al actief in maand ${maand}`)
                }

            }
    }


    static async checkDataAanwezig(klantId, maand) {
        const collection = 'dummyData'
        const query = klantId && maand ? {klantId, maand} : {}
        console.log(`zoek naar aanwezigheid van klantId: ${klantId} met maand ${maand}`)
        try {
            await client.connect()
            const data = await client.db(mongodb)
                                     .collection(collection)
                                     .find(query)
                                     .toArray()
            if (data.length > 0) {
                console.log(`Data is al opgehaald en aanwezig van maand ${maand} voor klant: ${klantId}`)
                return true
            } else {
                console.log(`Nog geen data aanwezig voor maand ${maand} en klant ${klantId}`)
                return false
            }
        } catch (err) {
            throw new Error(err.message)
        } finally {
            await client.close()
        }
    }

    static async maakDummyData(klantId, aantal) {
        const datum = new Date()
        const jaar =  datum.getFullYear()
        const maand = datum.getMonth()+2
        const totalYieldArrayPromise = dummyDataApparaat.berekenTotalYield(klantId, aantal)
        const totalYieldArrayObject = await totalYieldArrayPromise
        const totalSurplusPromise = dummyDataApparaat.berekenTotalSurplus(klantId, aantal)
        const totalSurplusObject = await totalSurplusPromise
        console.log(`OPVALLEN ${totalYieldArrayObject}, ${aantal} `)
        const randomString = crypto.randomBytes(5).toString('hex')
        const dummyData = {
            "message_id": randomString,
            "klantId": klantId,
            "status": "actief",
            "date": datum.toISOString(),
            "jaar": jaar,
            "maand": maand,
            "devices": []
        };

        for (let i = 0; i < aantal; i++) {
            const randomString = crypto.randomBytes(5).toString('hex')
            const maandelijkseOpbrengstZonnepaneel = dummyDataApparaat.maandelijkseOpbrengstZonnepaneel(maand)
            const maandelijkseOverschotZonnepaneel = dummyDataApparaat.maandelijkseOverschotZonnepaneel(maand)

            const device = { 
            "serial_number":  randomString,
            "device_type": "solar",
            "device_total_yield": (maandelijkseOpbrengstZonnepaneel + totalYieldArrayObject[i]),
            "device_month_yield": maandelijkseOpbrengstZonnepaneel,
            "device_total_surplus": maandelijkseOverschotZonnepaneel + totalSurplusObject[i],
            "device_month_surplus": maandelijkseOverschotZonnepaneel,
            };
            dummyData.devices.push(device);
        }
        return dummyData
    }


    static maandelijkseOpbrengstZonnepaneel(maand) {
        const maandelijkseOpbrengstPercentages = {
            "1": 0.03,
            "2": 0.05,
            "3": 0.08,
            "4": 0.12,
            "5": 0.13,
            "6": 0.13,
            "7": 0.13,
            "8": 0.11,
            "9": 0.10,
            "10": 0.07,
            "11": 0.03,
            "12": 0.02,
        }
        const maandelijkseOpbrengstPercentage = maandelijkseOpbrengstPercentages[maand]
        const maandelijkseOpbrengst = maandelijkseOpbrengstPercentage * 310
        const gemiddeldeMaandelijkseOpbrengst =  (Math.floor(Math.random() * ((maandelijkseOpbrengst+3) - (maandelijkseOpbrengst-3) + 1)) + (maandelijkseOpbrengst-3))
        return gemiddeldeMaandelijkseOpbrengst
    }

    static maandelijkseOverschotZonnepaneel(maand) {
        const maandelijkseOpbrengstPercentages = {
            "1": 0.03,
            "2": 0.05,
            "3": 0.08,
            "4": 0.12,
            "5": 0.13,
            "6": 0.13,
            "7": 0.13,
            "8": 0.11,
            "9": 0.10,
            "10": 0.07,
            "11": 0.03,
            "12": 0.02,
        }
        const maandelijkseOpbrengstPercentage = maandelijkseOpbrengstPercentages[maand]
        const maandelijkseOverschot = maandelijkseOpbrengstPercentage * 80
        const gemiddeldeMaandelijkseOverschot =  (Math.floor(Math.random() * ((maandelijkseOverschot+3) - (maandelijkseOverschot-3) + 1)) + (maandelijkseOverschot-3))
        return gemiddeldeMaandelijkseOverschot
    }

    static async berekenTotalYield(klantId, aantal) {
        const dataPromise = dummyDataApparaat.historischeDummyData(klantId);
        const dataObject = await dataPromise;
        console.log(aantal)
        if(dataObject === false){
    
            const totalYieldArray = new Array(3).fill(0);
            console.log(`ARRRRRR: ${totalYieldArray} en aantal: ${aantal}`)
            return totalYieldArray
        } 
        
        
        const devicesLength = dataObject[0].devices.length;
        console.log(devicesLength);  
        const totalYieldArray = [];
        let i = 0;
        for (i = 0; i < devicesLength; i++) {
            let total = 0;
            dataObject.forEach((datum, index) => {
                total += datum.devices[i].device_total_yield;
                console.log(`i: ${i}, data: ${datum.devices[i].device_total_yield}`)
            });
            totalYieldArray.push(total);
        }
        console.log(`array met total opbrengst voor ieder paneel [${totalYieldArray}]`);
        return totalYieldArray;
    }


    static async berekenTotalSurplus(klantId, aantal) {
        const dataPromise = dummyDataApparaat.historischeDummyData(klantId);
        const dataObject = await dataPromise;
        console.log(aantal)
        if(dataObject === false){
    
            const totalSurplusArray = new Array(3).fill(0);
            console.log(`ARRRRRR: ${totalSurplusArray} en aantal: ${aantal}`)
            return totalSurplusArray
        } 
        
        
        const devicesLength = dataObject[0].devices.length;
        console.log(devicesLength);  
        const totalSurplusArray = [];
        let i = 0;
        for (i = 0; i < devicesLength; i++) {
            let total = 0;
            dataObject.forEach((data, index) => {
                total += data.devices[i].device_total_surplus;
                console.log(`i: ${i}, data: ${data.devices[i].device_total_yield}`)
            });
            totalSurplusArray.push(total);
        }
        console.log(`array met total opbrengst voor ieder paneel [${totalSurplusArray}]`);
        return totalSurplusArray;
    }
    
      


    static async historischeDummyData (klantId) {
        const collection = 'dummyData'
        const query = klantId ? {klantId} : {}
        console.log(`Bezig met ophalen van alle dummy data van klant ${klantId}`)
        try {
            await client.connect()
            const data = await client.db(mongodb)
                                    .collection(collection)
                                    .find(query)
                                    .toArray()
            if (data.length > 0) {
                console.log(`Historische dummy data voor klant ${data} gevonden`)
                return data
            } else {
                console.log(`Geen historische dummy data voor klant ${klantId} gevonden`)
                return false
            }
        } catch (err) {
            throw new Error (err.message)
        } finally {
            await client.close()
        }
    }

}