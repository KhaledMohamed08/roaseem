<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 

class SendSms {

    // public function sendSms($to ,$message)
    // {
    //     return [
    //         'to' => "$to",
    //         'message' => "$message",
    //         'user' => env('sms_username'),
    //         'pass' => env('sms_password'),
    //         'sender' => env('sms_sender')
    //     ];
    //     // $response = Http::post('https://www.jawalbsms.ws/api.php/sendsms', [
    //     //     'to' => "$to",
    //     //     'message' => "$message",
    //     //     'user' => env('sms_username'),
    //     //     'pass' => env('sms_password'),
    //     //     'sender' => env('sms_sender')
    //     // ]);

    //     // return $response;
    // }

    public function sendSms($to, $message)//: bool
    {
        $client = new Client();

        try {
            $response = $client->post(env('SMS_API_URL'), [
                'form_params' => [
                    'user' => env('SMS_API_USER'),
                    'pass' => env('SMS_API_PASS'),
                    'sender' => env('SMS_API_SENDER'),
                    'to' => $to,
                    'message' => "$message",
                ],
            ]);

            // return $response;
            // Check for successful response (adjust based on API documentation)
            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                // Handle error (log, throw exception, etc.)
                Log::error('SMS API error: ' . $response->getBody());
                return false;
            }
        } catch (\Exception $e) {
            // Handle exceptions (log, throw exception, etc.)
            Log::error('SMS API exception: ' + $e->getMessage());
            return false;
        }
    }
}