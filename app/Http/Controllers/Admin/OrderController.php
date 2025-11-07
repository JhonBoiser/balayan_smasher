<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;
use App\Mail\PaymentReceived;
use App\Mail\CustomOrderEmail;
use Illuminate\Support\Facades\Log;
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
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        // Send SMS notification based on new status
        try {
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
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }

        // Send email notification
        try {
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Order status updated! Customer notified via SMS and email.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->payment_status]);

        if ($request->payment_status === 'paid') {
            // Notify customer via SMS and email
            try {
                $this->smsService->sendPaymentReceived($order);
                Mail::to($order->user->email)->send(new PaymentReceived($order));
            } catch (\Exception $e) {
                Log::error('Payment notification failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Payment status updated and customer notified!');
    }

    // ✅ Send individual email notification
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

    // ✅ Send individual SMS notification
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
                $this->smsService->sendCustomSms($order, $customMessage);
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
                        $this->smsService->sendOrderUpdate($order);
                        break;
                }
            }

            return back()->with('success', 'SMS notification sent successfully to ' . $order->user->phone . '!');
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send SMS: ' . $e->getMessage());
        }
    }

    // ✅ Send custom email notification
    public function sendCustomEmail(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Send custom email
            Mail::to($order->user->email)
                ->send(new CustomOrderEmail(
                    $order,
                    $request->message,
                    $request->subject
                ));

            return back()->with('success', 'Custom email sent successfully to ' . $order->user->email . '!');
        } catch (\Exception $e) {
            Log::error('Custom email sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send custom email: ' . $e->getMessage());
        }
    }
}
