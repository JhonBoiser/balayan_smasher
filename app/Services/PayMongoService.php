<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PayMongoService
{
    protected $publicKey;
    protected $secretKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->publicKey = config('services.paymongo.public_key');
        $this->secretKey = config('services.paymongo.secret_key');
        $this->apiUrl = config('services.paymongo.api_url');

        if (!$this->publicKey || !$this->secretKey) {
            throw new Exception('PayMongo keys not configured. Please set PAYMONGO_PUBLIC_KEY and PAYMONGO_SECRET_KEY in .env');
        }
    }

    /**
     * Create a payment intent
     *
     * @param array $data Payment data
     * @return array
     */
    public function createPaymentIntent(array $data)
    {
        try {
            $payload = [
                'data' => [
                    'attributes' => [
                        'amount' => (int)($data['amount'] * 100), // Convert to centavos
                        'currency' => 'PHP', // Required field
                        'payment_method_allowed' => $data['payment_methods'] ?? ['card', 'gcash', 'grab_pay'],
                        'payment_method_options' => $this->getPaymentMethodOptions(),
                        'description' => $data['description'] ?? 'Order Payment',
                        'statement_descriptor' => 'Balayan Smashers',
                        'metadata' => [
                            'order_id' => (string)$data['order_id'],
                            'customer_email' => $data['email'],
                            'customer_name' => $data['name'],
                        ]
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->apiUrl}/payment_intents", $payload);

            if (!$response->successful()) {
                Log::error('PayMongo Payment Intent Creation Failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to create payment intent');
            }

            $responseData = $response->json();
            Log::info('PayMongo Payment Intent Created', [
                'intent_id' => $responseData['data']['id'],
                'order_id' => $data['order_id']
            ]);

            return [
                'success' => true,
                'data' => $responseData['data']
            ];
        } catch (Exception $e) {
            Log::error('PayMongo Service Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get payment method options
     *
     * @return array
     */
    protected function getPaymentMethodOptions()
    {
        return [
            'card' => [
                'networks' => ['visa', 'mastercard']
            ],
            'gcash' => [],
            'grab_pay' => []
        ];
    }

    /**
     * Attach payment method to payment intent
     *
     * @param string $intentId
     * @param string $paymentMethodId
     * @param string $returnUrl
     * @return array
     */
    public function attachPaymentMethod($intentId, $paymentMethodId, $returnUrl)
    {
        try {
            $payload = [
                'data' => [
                    'attributes' => [
                        'payment_method' => $paymentMethodId,
                        'return_url' => $returnUrl
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->apiUrl}/payment_intents/{$intentId}/attach", $payload);

            if (!$response->successful()) {
                Log::error('PayMongo Attach Payment Method Failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to attach payment method');
            }

            $responseData = $response->json();
            Log::info('PayMongo Payment Method Attached', [
                'intent_id' => $intentId
            ]);

            return [
                'success' => true,
                'data' => $responseData['data']
            ];
        } catch (Exception $e) {
            Log::error('PayMongo Attach Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve payment intent
     *
     * @param string $intentId
     * @return array
     */
    public function retrievePaymentIntent($intentId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->apiUrl}/payment_intents/{$intentId}");

            if (!$response->successful()) {
                throw new Exception('Failed to retrieve payment intent');
            }

            return [
                'success' => true,
                'data' => $response->json()['data']
            ];
        } catch (Exception $e) {
            Log::error('PayMongo Retrieve Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Confirm payment intent
     *
     * @param string $intentId
     * @return array
     */
    public function confirmPaymentIntent($intentId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->apiUrl}/payment_intents/{$intentId}/confirm");

            if (!$response->successful()) {
                Log::error('PayMongo Confirm Failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to confirm payment');
            }

            $responseData = $response->json()['data'];
            Log::info('PayMongo Payment Confirmed', [
                'intent_id' => $intentId,
                'status' => $responseData['attributes']['status']
            ]);

            return [
                'success' => true,
                'data' => $responseData
            ];
        } catch (Exception $e) {
            Log::error('PayMongo Confirm Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get client key for payment form
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Process webhook from PayMongo
     *
     * @param array $payload
     * @return bool
     */
    public function processWebhook(array $payload)
    {
        try {
            $eventType = $payload['data']['type'] ?? null;
            $attributes = $payload['data']['attributes'] ?? [];

            Log::info('PayMongo Webhook Received', [
                'type' => $eventType
            ]);

            if ($eventType === 'payment_intent.success') {
                return $this->handlePaymentSuccess($attributes);
            } elseif ($eventType === 'payment_intent.failed') {
                return $this->handlePaymentFailed($attributes);
            }

            return true;
        } catch (Exception $e) {
            Log::error('PayMongo Webhook Processing Error', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Handle successful payment webhook
     *
     * @param array $attributes
     * @return bool
     */
    protected function handlePaymentSuccess(array $attributes)
    {
        $metadata = $attributes['metadata'] ?? [];
        $orderId = $metadata['order_id'] ?? null;

        if ($orderId) {
            Log::info('PayMongo Payment Success Webhook', [
                'order_id' => $orderId,
                'status' => $attributes['status']
            ]);
            return true;
        }

        return false;
    }

    /**
     * Handle failed payment webhook
     *
     * @param array $attributes
     * @return bool
     */
    protected function handlePaymentFailed(array $attributes)
    {
        $metadata = $attributes['metadata'] ?? [];
        $orderId = $metadata['order_id'] ?? null;

        if ($orderId) {
            Log::warning('PayMongo Payment Failed Webhook', [
                'order_id' => $orderId,
                'status' => $attributes['status']
            ]);
            return true;
        }

        return false;
    }
}
