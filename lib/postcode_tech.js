

export default class klantgegevens {
    static ophalenKlantgegevens(postcode, huisnummer) {
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

}