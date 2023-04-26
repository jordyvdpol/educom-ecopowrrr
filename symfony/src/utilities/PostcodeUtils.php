<?php

namespace App\utilities;

use Exception;

class PostcodeUtils
{
    public static function fetchPostcodeData(string $postcode, $huisnummer)
    {
        $url = 'https://postcode.tech/api/v1/postcode/full?postcode=' . urlencode($postcode) . '&number=' . urlencode($huisnummer);
        $bearerToken = 'e1f29cae-b9b8-4ddd-b3dd-0fd976394914';

        $headers = [
            'Authorization: Bearer ' . $bearerToken
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }
        $data = json_decode($response, true);
        curl_close($ch);
        
        return $data;
    }
}
