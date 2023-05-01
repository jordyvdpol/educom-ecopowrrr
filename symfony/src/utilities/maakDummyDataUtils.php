<?php

namespace App\utilities;
use Exception;

class maakDummyDataUtils
{
    public static function maakDummyData($klant_id, $aantal, $jaar, $maand) {
        $url = 'http://localhost:3000/maakDummyData?id=' . urlencode($klant_id) . '&aantal=' . urlencode($aantal) . '&jaar=' . urlencode($jaar) . '&maand=' . urlencode($maand);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }

        curl_close($ch);

        $total_yield = 0;
        $month_yield = 0;
        $total_surplus = 0;
        $month_surplus = 0;
        $data = json_decode($response, true);
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
    }
}
?>

