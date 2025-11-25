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
        $this->client = new Client([
            'timeout' => 30,
            'http_errors' => false // Don't throw exceptions on 4xx/5xx
        ]);

        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
        $this->apiUrl = config('services.semaphore.api_url');
    }

    /**
     * Send SMS message
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array
     */
    public function send($phoneNumber, $message)
    {
        // Check if API key is configured
        if (empty($this->apiKey)) {
            Log::warning('SMS not sent: SEMAPHORE_API_KEY not configured');
            return [
                'success' => false,
                'error' => 'SMS service not configured. Please set SEMAPHORE_API_KEY in .env file'
            ];
        }

        try {
            // Format phone number (remove +63, ensure starts with 0)
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // Validate phone number
            if (!$this->isValidPhoneNumber($phoneNumber)) {
                Log::error('Invalid phone number format: ' . $phoneNumber);
                return [
                    'success' => false,
                    'error' => 'Invalid phone number format'
                ];
            }

            // Truncate message if too long
            if (strlen($message) > 160) {
                $message = substr($message, 0, 157) . '...';
            }

            Log::info('Sending SMS', [
                'to' => $phoneNumber,
                'message' => $message,
                'api_url' => $this->apiUrl
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

            // Check if successful
            if ($statusCode == 200 || $statusCode == 201) {
                // Semaphore returns array of message objects
                if (is_array($result) && count($result) > 0) {
                    $messageId = $result[0]['message_id'] ?? null;

                    Log::info('SMS Sent Successfully', [
                        'phone' => $phoneNumber,
                        'message_id' => $messageId
                    ]);

                    return [
                        'success' => true,
                        'message_id' => $messageId,
                        'data' => $result
                    ];
                }
            }

            // Failed response
            Log::error('SMS Send Failed', [
                'status_code' => $statusCode,
                'response' => $result
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send SMS: ' . ($result['message'] ?? 'Unknown error'),
                'response' => $result
            ];

        } catch (Exception $e) {
            Log::error('SMS Send Exception: ' . $e->getMessage(), [
                'phone' => $phoneNumber,
                'message' => $message,
                'exception' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'SMS service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format Philippine phone number
     * Converts various formats to 09XXXXXXXXX
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove country code +63 or 63
        if (substr($phone, 0, 3) === '639') {
            $phone = '0' . substr($phone, 2);
        } elseif (substr($phone, 0, 2) === '63') {
            $phone = '0' . substr($phone, 2);
        }

        // Add leading 0 if not present
        if (substr($phone, 0, 1) !== '0') {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Validate Philippine phone number format
     * Must be 11 digits starting with 09
     *
     * @param string $phone
     * @return bool
     */
    protected function isValidPhoneNumber($phone)
    {
        // Must be 11 digits and start with 09
        return preg_match('/^09\d{9}$/', $phone);
    }

    /**
     * Check account balance
     *
     * @return array|null
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

            Log::info('SMS Balance Check', ['balance' => $result]);

            return $result;

        } catch (Exception $e) {
            Log::error('Balance Check Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test SMS configuration
     *
     * @return array
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

        // Try to check balance
        $balance = $this->checkBalance();

        if ($balance && isset($balance['account_id'])) {
            return [
                'configured' => true,
                'message' => 'SMS service is properly configured',
                'account_id' => $balance['account_id'],
                'account_name' => $balance['account_name'] ?? 'N/A',
                'credits' => $balance['credits'] ?? 'N/A'
            ];
        }

        return [
            'configured' => false,
            'message' => 'SMS service configured but unable to verify. Check API key.'
        ];
    }
}
