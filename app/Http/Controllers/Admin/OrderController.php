<?php

// app/Http/Controllers/Admin/OrderController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusUpdated;
use App\Mail\PaymentReceived;
use Illuminate\Support\Facades\Log;
use App\Services\OrderSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(OrderSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product.primaryImage'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Check for order updates (for real-time sync)
     */
    public function checkUpdates($id)
    {
        $order = Order::with(['user', 'items.product'])
            ->findOrFail($id);

        return response()->json([
            'updated' => true,
            'updated_at' => $order->updated_at->toIso8601String(),
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'tracking_number' => $order->tracking_number,
                'total' => $order->total,
                'updated_at' => $order->updated_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Update order status (AJAX)
     */
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
            'tracking_number' => $request->tracking_number
        ]);

        // Send appropriate notifications based on status
        try {
            // Send Email
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));

            // Send SMS
            switch ($newStatus) {
                case 'processing':
                    $this->smsService->sendOrderProcessing($order);
                    break;
                case 'shipped':
                    $this->smsService->sendOrderShipped($order, $request->tracking_number);
                    break;
                case 'delivered':
                    $this->smsService->sendOrderDelivered($order);
                    break;
                case 'cancelled':
                    $this->smsService->sendOrderCancelled($order);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully!',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'updated_at' => $order->updated_at->toIso8601String(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Order notification error: ' . $e->getMessage());

            return response()->json([
                'success' => true,
                'message' => 'Order status updated but notifications failed to send.',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'updated_at' => $order->updated_at->toIso8601String(),
                ]
            ]);
        }
    }

    /**
     * Update payment status (AJAX)
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order = Order::findOrFail($id);
        $oldPaymentStatus = $order->payment_status;

        $order->update(['payment_status' => $request->payment_status]);

        // Send notifications if payment is confirmed
        if ($request->payment_status === 'paid' && $oldPaymentStatus !== 'paid') {
            try {
                // Send Email
                Mail::to($order->user->email)
                    ->send(new PaymentReceived($order));

                // Send SMS
                $this->smsService->sendPaymentReceived($order);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated! Customer notified.'
                ]);
            } catch (\Exception $e) {
                Log::error('Payment notification error: ' . $e->getMessage());

                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated but notifications failed.'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!'
        ]);
    }

    /**
     * Send custom email to customer (AJAX)
     */
    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $order = Order::findOrFail($id);

        try {
            Mail::raw($request->message, function($msg) use ($order, $request) {
                $msg->to($order->user->email)
                    ->subject($request->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // Log the email
            Log::info('Custom email sent', [
                'order_id' => $order->id,
                'to' => $order->user->email,
                'subject' => $request->subject
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully to ' . $order->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Email send error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send custom SMS to customer (AJAX)
     */
    public function sendSms(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:160'
        ]);

        $order = Order::findOrFail($id);

        try {
            $result = $this->smsService->sendCustomMessage($order, $request->message);

            if ($result['success']) {
                // Log the SMS
                Log::info('Custom SMS sent', [
                    'order_id' => $order->id,
                    'to' => $order->shipping_phone,
                    'message' => $request->message
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully to ' . $order->shipping_phone
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . ($result['error'] ?? 'Unknown error')
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('SMS send error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with('user', 'items');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $filename = 'orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Date',
                'Items',
                'Subtotal',
                'Shipping',
                'Total',
                'Payment Method',
                'Payment Status',
                'Order Status'
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->items->count(),
                    number_format((float)$order->subtotal, 2),
                    number_format((float)$order->shipping_fee, 2),
                    number_format((float)$order->total, 2),
                    $order->payment_method,
                    $order->payment_status,
                    $order->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete order (soft delete or hard delete)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Check if order can be deleted
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            $order->delete();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully!');
        }

        return back()->with('error', 'Cannot delete active orders. Please cancel the order first.');
    }

    /**
     * Bulk status update
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            $oldStatus = $order->status;
            $order->update(['status' => $request->status]);

            // Send notifications
            try {
                Mail::to($order->user->email)
                    ->send(new OrderStatusUpdated($order, $oldStatus, $request->status));

                // Send appropriate SMS
                switch ($request->status) {
                    case 'processing':
                        $this->smsService->sendOrderProcessing($order);
                        break;
                    case 'shipped':
                        $this->smsService->sendOrderShipped($order);
                        break;
                    case 'delivered':
                        $this->smsService->sendOrderDelivered($order);
                        break;
                }
            } catch (\Exception $e) {
                Log::error('Bulk notification error for order ' . $order->id . ': ' . $e->getMessage());
            }
        }

        return back()->with('success', count($orders) . ' orders updated successfully!');
    }
}

// ============================================
// ADD THESE ROUTES TO routes/web.php
// ============================================
/*
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing admin routes ...

    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('payment');

        // New routes for email and SMS
        Route::post('/{id}/send-email', [AdminOrderController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/send-sms', [AdminOrderController::class, 'sendSms'])->name('send-sms');

        // Additional features
        Route::get('/export', [AdminOrderController::class, 'export'])->name('export');
        Route::post('/bulk-update', [AdminOrderController::class, 'bulkUpdateStatus'])->name('bulk-update');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });
});
*/

// ============================================
// UPDATE ORDERS TABLE MIGRATION (if needed)
// Add tracking_number column
// ============================================
/*
Schema::table('orders', function (Blueprint $table) {
    $table->string('tracking_number')->nullable()->after('order_number');
});
*/
