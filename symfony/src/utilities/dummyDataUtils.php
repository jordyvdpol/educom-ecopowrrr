<?php

namespace App\utilities;

class uitlezenData
{
    public static function uitlezenDummyData($klant_id) {
        $url = 'http://localhost:3000/uitlezenDummyData?klantId=' . urlencode($klant_id);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }
        $data = json_decode($response, true);
        curl_close($ch);
        
        return $data;
    
        //functie test command line:
        // php -r "require '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/src/utilities/dummyDataUtils.php'; echo json_encode(App\utilities\uitlezenData::uitlezenDummyData('7'));"

    }
}

?>

