<?php

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
        $this->client = new Client([
            'timeout' => 30,
            'http_errors' => false // Prevent throwing exceptions on 4xx/5xx
        ]);

        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
        $this->apiUrl = config('services.semaphore.api_url');
    }

    /**
     * Send SMS message
     */
    public function send($phoneNumber, $message)
    {
        if (empty($this->apiKey)) {
            Log::warning('SMS not sent: SEMAPHORE_API_KEY not configured');
            return [
                'success' => false,
                'error' => 'SMS service not configured. Please set SEMAPHORE_API_KEY in .env file'
            ];
        }

        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            if (!$this->isValidPhoneNumber($phoneNumber)) {
                Log::error('Invalid phone number format: ' . $phoneNumber);
                return [
                    'success' => false,
                    'error' => 'Invalid phone number format'
                ];
            }

            if (strlen($message) > 160) {
                $message = substr($message, 0, 157) . '...';
            }

            Log::info('Sending SMS', [
                'to' => $phoneNumber,
                'message' => $message,
            ]);

            $response = $this->client->post($this->apiUrl, [
                'form_params' => [
                    'apikey' => $this->apiKey,
                    'number' => $phoneNumber,
                    'message' => $message,
                    'sendername' => $this->senderName,
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $result = json_decode($body, true);

            Log::info('SMS API Response', [
                'status_code' => $statusCode,
                'response' => $result
            ]);

            if ($statusCode == 200 || $statusCode == 201) {
                if (is_array($result) && count($result) > 0) {
                    return [
                        'success' => true,
                        'message_id' => $result[0]['message_id'] ?? null,
                        'data' => $result
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Failed to send SMS: ' . ($result['message'] ?? 'Unknown error'),
                'response' => $result
            ];

        } catch (Exception $e) {
            Log::error('SMS Send Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'SMS service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check account balance
     */
    public function checkBalance()
    {
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            $response = $this->client->get('https://api.semaphore.co/api/v4/account', [
                'query' => ['apikey' => $this->apiKey]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('SMS Balance Check', ['balance_response' => $result]);

            // Auto-detect credit/balance field
            $credits = $result['balance']
                ?? $result['credits']
                ?? $result['credit_balance']
                ?? 'N/A';

            // Normalize output
            return [
                'account_id' => $result['account_id'] ?? null,
                'account_name' => $result['email'] ?? 'Unknown',
                'credits' => $credits,
            ];
        } catch (Exception $e) {
            Log::error('Balance Check Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test configuration and check balance
     */
    public function testConfiguration()
    {
        if (empty($this->apiKey)) {
            return [
                'configured' => false,
                'message' => 'SEMAPHORE_API_KEY not set in .env file'
            ];
        }

        if (empty($this->senderName)) {
            return [
                'configured' => false,
                'message' => 'SEMAPHORE_SENDER_NAME not set in .env file'
            ];
        }

        $balance = $this->checkBalance();

        if ($balance && isset($balance['account_id'])) {
            return [
                'configured' => true,
                'message' => 'SMS service is properly configured',
                'account_id' => $balance['account_id'],
                'account_name' => $balance['account_name'],
                'credits' => $balance['credits'],
            ];
        }

        return [
            'configured' => false,
            'message' => 'SMS service configured but unable to verify. Check API key or internet connection.'
        ];
    }

    /**
     * Format phone number
     */
    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 3) === '639') {
            $phone = '0' . substr($phone, 2);
        } elseif (substr($phone, 0, 2) === '63') {
            $phone = '0' . substr($phone, 2);
        }

        if (substr($phone, 0, 1) !== '0') {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Validate phone number format
     */
    protected function isValidPhoneNumber($phone)
    {
        return preg_match('/^09\d{9}$/', $phone);
    }
}
