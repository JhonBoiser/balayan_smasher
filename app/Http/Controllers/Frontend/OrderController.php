<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderStatusUpdated;
use App\Mail\PaymentReceived;
use App\Services\OrderSmsService;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(OrderSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product.primaryImage'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        // ✅ Send SMS notification based on new status
        switch ($newStatus) {
            case 'processing':
                $this->smsService->sendOrderProcessing($order);
                break;

            case 'shipped':
                $this->smsService->sendOrderShipped($order, $request->tracking_number ?? null);
                break;

            case 'delivered':
                $this->smsService->sendOrderDelivered($order);
                break;

            case 'cancelled':
                $this->smsService->sendOrderCancelled($order);
                break;
        }

        // ✅ Send email notification
        Mail::to($order->user->email)
            ->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));

        return back()->with('success', 'Order status updated! Customer notified via SMS and email.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->payment_status]);

        if ($request->payment_status === 'paid') {
            // ✅ Notify customer via SMS and email
            $this->smsService->sendPaymentReceived($order);
            Mail::to($order->user->email)->send(new PaymentReceived($order));
        }

        return back()->with('success', 'Payment status updated and customer notified!');
    }

    // ✅ NEW METHOD: Send individual email notification
    public function sendEmail(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        try {
            // Send status update email
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order, $order->status, $order->status));

            return back()->with('success', 'Email notification sent successfully to ' . $order->user->email . '!');
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    // ✅ NEW METHOD: Send individual SMS notification
    public function sendSms(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Check if customer has phone number
        if (empty($order->user->phone)) {
            return back()->with('error', 'Cannot send SMS: Customer phone number not available.');
        }

        try {
            $customMessage = $request->custom_message;

            if (!empty($customMessage)) {
                // Send custom SMS message
               // $this->smsService->sendCustomSms($order, $customMessage);
            } else {
                // Send SMS based on current order status
                switch ($order->status) {
                    case 'processing':
                        $this->smsService->sendOrderProcessing($order);
                        break;
                    case 'shipped':
                        $this->smsService->sendOrderShipped($order, $order->tracking_number);
                        break;
                    case 'delivered':
                        $this->smsService->sendOrderDelivered($order);
                        break;
                    case 'cancelled':
                        $this->smsService->sendOrderCancelled($order);
                        break;
                    default:
                        // Send a generic order update SMS
                       // $this->smsService->sendOrderUpdate($order);
                        break;
                }
            }

            return back()->with('success', 'SMS notification sent successfully to ' . $order->user->phone . '!');
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send SMS: ' . $e->getMessage());
        }
    }
}
