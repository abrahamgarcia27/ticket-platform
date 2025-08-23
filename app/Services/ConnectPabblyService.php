<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ConnectPabblyService
{
    public function sendOrderData($order_data)
    {
        $curl = curl_init();
        $data = json_encode($order_data, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjYwNTZhMDYzZTA0M2Q1MjZkNTUzNjUxMzQi_pc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: ci_connect_pabbly_sesion=ved51j9irl09l5r09pfi3fjdmqjdfdb2'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
}