<?php
// ============================================
// ORDER SMS SERVICE
// app/Services/OrderSmsService.php
// ============================================
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderSmsService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send order placed SMS
     */
    public function sendOrderPlaced(Order $order)
    {
        $message = "Hi {$order->shipping_name}! Your order #{$order->order_number} has been received. "
                 . "Total: ₱" . number_format((float)$order->total, 2) . ". "
                 . "Thank you for shopping at Balayan Smashers Hub!";

        return $this->sendSms($order, $message, 'Order Placed');
    }

    /**
     * Send order processing SMS
     */
    public function sendOrderProcessing(Order $order)
    {
        $message = "Good news! Your order #{$order->order_number} is now being processed. "
                 . "We'll notify you once it ships. - Balayan Smashers Hub";

        return $this->sendSms($order, $message, 'Order Processing');
    }

    /**
     * Send order shipped SMS
     */
    public function sendOrderShipped(Order $order, $trackingNumber = null)
    {
        $message = "Your order #{$order->order_number} has been shipped! ";

        if ($trackingNumber) {
            $message .= "Tracking: {$trackingNumber}. ";
        }

        $message .= "Expected delivery: 3-5 days. - Balayan Smashers Hub";

        return $this->sendSms($order, $message, 'Order Shipped');
    }

    /**
     * Send order delivered SMS
     */
    public function sendOrderDelivered(Order $order)
    {
        $message = "Your order #{$order->order_number} has been delivered! "
                 . "We hope you enjoy your purchase. Thank you for choosing Balayan Smashers Hub!";

        return $this->sendSms($order, $message, 'Order Delivered');
    }

    /**
     * Send payment received SMS
     */
    public function sendPaymentReceived(Order $order)
    {
        $message = "Payment confirmed for order #{$order->order_number}. "
                 . "Amount: ₱" . number_format((float)$order->total, 2) . ". "
                 . "Your order will be processed shortly. - Balayan Smashers Hub";

        return $this->sendSms($order, $message, 'Payment Received');
    }

    /**
     * Send order cancelled SMS
     */
    public function sendOrderCancelled(Order $order)
    {
        $message = "Your order #{$order->order_number} has been cancelled. "
                 . "If you have questions, call us at +63 906 623 8257. - Balayan Smashers Hub";

        return $this->sendSms($order, $message, 'Order Cancelled');
    }

    /**
     * Send payment reminder SMS
     */
    public function sendPaymentReminder(Order $order)
    {
        $message = "Reminder: Payment pending for order #{$order->order_number}. "
                 . "Total: ₱" . number_format((float)$order->total, 2) . ". "
                 . "Please settle payment to process your order. - Balayan Smashers Hub";

        return $this->sendSms($order, $message, 'Payment Reminder');
    }

    /**
     * Send custom SMS to customer
     */
    public function sendCustomMessage(Order $order, $message)
    {
        $fullMessage = "Balayan Smashers Hub: {$message} - Order #{$order->order_number}";

        return $this->sendSms($order, $fullMessage, 'Custom Message');
    }

    /**
     * Send SMS (internal helper method)
     *
     * @param Order $order
     * @param string $message
     * @param string $type
     * @return array
     */
    protected function sendSms(Order $order, $message, $type = 'SMS')
    {
        $phone = $order->shipping_phone;

        // Validate phone number exists
        if (empty($phone)) {
            Log::warning("No phone number for order #{$order->order_number}");
            return [
                'success' => false,
                'error' => 'No phone number provided'
            ];
        }

        Log::info("Sending {$type} SMS", [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'phone' => $phone,
            'type' => $type
        ]);

        $result = $this->smsService->send($phone, $message);

        if ($result['success']) {
            Log::info("{$type} SMS sent successfully", [
                'order_id' => $order->id,
                'message_id' => $result['message_id'] ?? null
            ]);
        } else {
            Log::error("{$type} SMS failed", [
                'order_id' => $order->id,
                'error' => $result['error'] ?? 'Unknown error'
            ]);
        }

        return $result;
    }

    /**
     * Send low stock alert to admin
     */
    public function sendLowStockAlert($products, $adminPhone)
    {
        $productNames = $products->pluck('name')->take(3)->implode(', ');

        $message = "LOW STOCK ALERT: {$products->count()} products need restocking including: {$productNames}. "
                 . "Check admin panel for details.";

        return $this->smsService->send($adminPhone, $message);
    }
}
