<?php

namespace App\utilities;
use Exception;

class activeerDummyApparaatUtils{
    public static function activatiebericht($status, $klantnummer, $aantal){
        $response = false;
        $url = 'http://localhost:3000/activeerKlant?status=' . urlencode($status) . '&id=' . urlencode($klantnummer) . '&aantal=' . urlencode($aantal);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }

}

       //functie test command line:
        // php -r "require '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/src/utilities/activeerDummyApparaatUtils.php'; echo json_encode(App\utilities\uitlezenData::uitlezenDummyData('actief', '4', '5'));"
?>