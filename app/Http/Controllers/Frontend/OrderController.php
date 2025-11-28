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

    /**
     * Display a listing of the customer's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product.primaryImage'])
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with([
            'user',
            'items.product.primaryImage'
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Cancel order (Customer action)
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled. Only pending or processing orders with unpaid status can be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|in:changed_mind,found_cheaper,shipping_issues,product_unavailable,payment_issues,other',
            'custom_reason' => 'required_if:cancellation_reason,other|max:500'
        ]);

        $reason = $request->cancellation_reason;
        if ($reason === 'other' && $request->custom_reason) {
            $reason = $request->custom_reason;
        }

        // Cancel the order
        $order->cancel($reason, 'customer');

        // Send notifications
        try {
            // Send SMS notification
            $this->smsService->sendOrderCancelled($order);

            // Send email notification to customer
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order, $order->status, 'cancelled'));

            // Send email notification to admin (you might want to create a separate email for this)
            // Mail::to(config('mail.admin_email'))
            //     ->send(new OrderCancelledNotification($order));

        } catch (\Exception $e) {
            Log::error('Cancellation notification failed: ' . $e->getMessage());
        }

        // Log the cancellation
        Log::info('Order cancelled by customer', [
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'reason' => $reason
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Show cancellation form
     */
    public function showCancelForm($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        return view('frontend.orders.cancel', compact('order'));
    }

    /**
     * Admin: Cancel order
     */
    public function adminCancelOrder(Request $request, $id)
    {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::findOrFail($id);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $order->cancel($request->cancellation_reason, 'admin');

        // Send notifications
        try {
            $this->smsService->sendOrderCancelled($order);
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order, $order->status, 'cancelled'));
        } catch (\Exception $e) {
            Log::error('Admin cancellation notification failed: ' . $e->getMessage());
        }

        Log::info('Order cancelled by admin', [
            'order_id' => $order->id,
            'admin_id' => auth()->id(),
            'reason' => $request->cancellation_reason
        ]);

        return back()->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Admin: Show cancellation form
     */
    public function showAdminCancelForm($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::with('user')->findOrFail($id);
        return view('admin.orders.cancel', compact('order'));
    }

    // ... rest of your existing methods ...
}
