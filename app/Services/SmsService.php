<?php

// ============================================
// SMS SERVICE
// app/Services/SmsService.php
// ============================================
namespace App\Services;

use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;


class SmsService
{
    protected $client;
    protected $apiKey;
    protected $senderName;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
        $this->apiUrl = config('services.semaphore.api_url');
    }

    /**
     * Send SMS message
     */
    public function send($phoneNumber, $message)
    {
        try {
            // Format phone number (remove +63, add leading 0 if needed)
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $response = $this->client->post($this->apiUrl, [
                'form_params' => [
                    'apikey' => $this->apiKey,
                    'number' => $phoneNumber,
                    'message' => $message,
                    'sendername' => $this->senderName,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('SMS Sent Successfully', [
                'phone' => $phoneNumber,
                'response' => $result
            ]);

            return [
                'success' => true,
                'message_id' => $result[0]['message_id'] ?? null,
                'data' => $result
            ];

        } catch (Exception $e) {
            Log::error('SMS Send Error: ' . $e->getMessage(), [
                'phone' => $phoneNumber,
                'message' => $message
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS
     */
    public function sendBulk(array $recipients)
    {
        try {
            $response = $this->client->post($this->apiUrl, [
                'json' => [
                    'apikey' => $this->apiKey,
                    'sendername' => $this->senderName,
                    'messages' => array_map(function($recipient) {
                        return [
                            'number' => $this->formatPhoneNumber($recipient['phone']),
                            'message' => $recipient['message']
                        ];
                    }, $recipients)
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            Log::error('Bulk SMS Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Format Philippine phone number
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading +63
        if (substr($phone, 0, 2) === '63') {
            $phone = '0' . substr($phone, 2);
        }

        // Add leading 0 if not present
        if (substr($phone, 0, 1) !== '0') {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Check account balance
     */
    public function checkBalance()
    {
        try {
            $response = $this->client->get('https://api.semaphore.co/api/v4/account', [
                'query' => ['apikey' => $this->apiKey]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            Log::error('Balance Check Error: ' . $e->getMessage());
            return null;
        }
    }
}
