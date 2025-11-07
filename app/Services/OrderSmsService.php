<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderSmsService
{
    /**
     * Send order processing notification
     */
    public function sendOrderProcessing(Order $order)
    {
        $message = "Hi {$order->user->name}, your order #{$order->order_number} is being processed. We'll notify you when it ships. Thank you!";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send order shipped notification
     */
    public function sendOrderShipped(Order $order, $trackingNumber = null)
    {
        $trackingInfo = $trackingNumber ? " Tracking #: {$trackingNumber}" : "";
        $message = "Hi {$order->user->name}, your order #{$order->order_number} has been shipped.{$trackingInfo} Expected delivery: 3-5 business days.";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send order delivered notification
     */
    public function sendOrderDelivered(Order $order)
    {
        $message = "Hi {$order->user->name}, your order #{$order->order_number} has been delivered. Thank you for shopping with us!";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send order cancelled notification
     */
    public function sendOrderCancelled(Order $order)
    {
        $message = "Hi {$order->user->name}, your order #{$order->order_number} has been cancelled. Contact support for details.";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send payment received notification
     */
    public function sendPaymentReceived(Order $order)
    {
        $message = "Hi {$order->user->name}, payment for order #{$order->order_number} has been confirmed. Thank you!";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send generic order update notification
     */
    public function sendOrderUpdate(Order $order)
    {
        $message = "Hi {$order->user->name}, your order #{$order->order_number} status has been updated to: " . ucfirst($order->status) . ".";
        return $this->sendCustomSms($order, $message);
    }

    /**
     * Send custom SMS message
     */
    public function sendCustomSms(Order $order, $message)
    {
        if (empty($order->user->phone)) {
            throw new \Exception('No phone number available for this customer.');
        }

        $phone = $this->formatPhoneNumber($order->user->phone);

        Log::info("SMS would be sent to {$phone}: {$message}");


        return $this->sendViaSmsGateway($phone, $message);
    }

    /**
     * Format phone number for SMS gateway
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if missing (Philippines +63)
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '9') {
            $phone = '63' . $phone;
        }

        return $phone;
    }

    /**
     * Send SMS via actual gateway (implement based on your provider)
     */
    protected function sendViaSmsGateway($phone, $message)
    {
        // Example using Semaphore SMS API
        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => env('d9925070033a48661de6ea5482fc9263'),
            'number' => $phone,
            'message' => $message,
            'sendername' => env('SMS_SENDER_NAME', 'YourStore')
        ]);

        if (!$response->successful()) {
            throw new \Exception('SMS gateway error: ' . $response->body());
        }

        return $response->json();


        // For now, just log the SMS (remove this when implementing real SMS)
        Log::info("SMS Notification", [
            'to' => $phone,
            'message' => $message,
            'length' => strlen($message)
        ]);

        return ['success' => true, 'message' => 'SMS logged successfully'];
    }

    /**
     * Generate status-based message
     */
    protected function generateStatusMessage(Order $order)
    {
        switch ($order->status) {
            case 'processing':
                return "Hi {$order->user->name}, your order #{$order->order_number} is being processed. We'll notify you when it ships.";
            case 'shipped':
                $tracking = $order->tracking_number ? " Tracking: {$order->tracking_number}" : "";
                return "Hi {$order->user->name}, your order #{$order->order_number} has been shipped.{$tracking}";
            case 'delivered':
                return "Hi {$order->user->name}, your order #{$order->order_number} has been delivered. Thank you for shopping with us!";
            case 'cancelled':
                return "Hi {$order->user->name}, your order #{$order->order_number} has been cancelled. Contact support for details.";
            default:
                return "Hi {$order->user->name}, your order #{$order->order_number} status has been updated to {$order->status}.";
        }
    }
}
