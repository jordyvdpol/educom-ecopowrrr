<?php

namespace App\utilities;
use Exception;

class uitlezenDataUtils
{
    public static function uitlezenDummyData($klant_id) {
        $url = 'http://localhost:3000/uitlezenDummyData?id=' . urlencode($klant_id);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }

        $data = json_decode($response, true);
        curl_close($ch);
        
        $total_yield = 0;
        $month_yield = 0;
        $total_surplus = 0;
        $month_surplus = 0;

        foreach ($data['devices'] as $device) {
            $total_yield += (float) $device['device_total_yield'];
            $month_yield += (float) $device['device_month_yield'];
            $total_surplus += (float) $device['device_total_surplus'];
            $month_surplus += (float) $device['device_month_surplus'];
        }
        
        $dummyData = [
            'klantnummer_id' => $data['klantId'],
            'message_id' => $data['message_id'],
            'status' => $data['status'],
            'date' => $data['date'],
            'jaar' => $data['jaar'],
            'maand' => $data['maand'],
            'total_yield' => $total_yield,
            'month_yield' => $month_yield,
            'total_surplus' => $total_surplus,
            'month_surplus' => $month_surplus,
        ];


        return $dummyData;
    
        //functie test command line:
        // php -r "require '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/src/utilities/dummyDataUtils.php'; echo json_encode(App\utilities\uitlezenData::uitlezenDummyData('7'));"

    }
}

?>

