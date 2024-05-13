<?php

namespace App\Services;

use GuzzleHttp\Client;

class Payment
{
    protected $client;
    protected $apiId;
    protected $secretKey;
    protected $token;

    public function __construct($apiId, $secretKey)
    {
        // Initialize Guzzle HTTP client
        $this->client = new Client([
            'base_uri' => 'https://restpilot.paylink.sa.', // Example base URI for Paylink API
            'timeout'  => 10, // Adjust timeout as needed
        ]);

        // Set API credentials
        $this->apiId = $apiId;
        $this->secretKey = $secretKey;

        // Authenticate and obtain token
        $this->authenticate();
    }

    protected function authenticate()
    {
        try {
            $response = $this->client->post('auth', [
                'json' => [
                    'apiId' => $this->apiId,
                    'secretKey' => $this->secretKey,
                    'persistToken' => false
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Check if authentication was successful and token is received
            if (isset($data['id_token'])) {
                $this->token = $data['id_token'];
            } else {
                throw new \Exception("Authentication failed. No token received.");
            }
        } catch (\Exception $e) {
            // Handle authentication error
            // You might want to log the error or throw an exception
            dd($e->getMessage());
        }
    }

    public function createInvoice($invoiceData)
    {
        try {
            $response = $this->client->post('invoice', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'accept' => 'application/json',
                    'content-type' => 'application/json'
                ],
                'json' => $invoiceData
            ]);

            $data = json_decode($response->getBody(), true);

            // Check if invoice creation was successful
            if (isset($data['success']) && $data['success']) {
                // Redirect user to the mobileUrl
                return $data['mobileUrl'];
            } else {
                throw new \Exception("Failed to create invoice: " . $data['error']);
            }
        } catch (\Exception $e) {
            // Handle invoice creation error
            // You might want to log the error or throw an exception
            dd($e->getMessage());
        }
    }

    // Other methods for payment handling...
}
