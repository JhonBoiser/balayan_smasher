<?php

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
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::with([
            'user',
            'items.product.primaryImage',
            'items.product.images'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Get recent orders for notifications (AJAX)
     */
    public function getRecentOrders()
    {
        try {
            $recentOrders = Order::with('user')
                ->where('created_at', '>=', now()->subHours(24)) // Last 24 hours
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->user->name,
                        'total' => $order->total,
                        'status' => $order->status,
                        'created_at' => $order->created_at->toISOString(),
                        'time_ago' => $order->created_at->diffForHumans()
                    ];
                });

            return response()->json($recentOrders);
        } catch (\Exception $e) {
            Log::error('Error fetching recent orders: ' . $e->getMessage());

            return response()->json([], 500);
        }
    }

    /**
     * Check for order updates (for real-time sync)
     */
    public function checkUpdates($id)
    {
        try {
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
                    'subtotal' => $order->subtotal,
                    'shipping_fee' => $order->shipping_fee,
                    'updated_at' => $order->updated_at->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking order updates: ' . $e->getMessage());

            return response()->json([
                'updated' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }

    /**
     * Update order status (AJAX)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
                'tracking_number' => 'nullable|string|max:255'
            ]);

            $order = Order::with('user')->findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Prepare update data
            $updateData = ['status' => $newStatus];
            if ($request->has('tracking_number')) {
                $updateData['tracking_number'] = $request->tracking_number;
            }

            $order->update($updateData);

            $response = [
                'success' => true,
                'message' => 'Order status updated successfully!',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'tracking_number' => $order->tracking_number,
                    'updated_at' => $order->updated_at->toIso8601String(),
                ]
            ];

            // Send appropriate notifications based on status
            try {
                // Send Email
                Mail::to($order->user->email)
                    ->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));

                // Send SMS
                switch ($newStatus) {
                    case 'processing':
                        $this->smsService->sendOrderProcessing($order);
                        $response['message'] .= ' Customer notified via email and SMS.';
                        break;
                    case 'shipped':
                        $this->smsService->sendOrderShipped($order, $request->tracking_number);
                        $response['message'] .= ' Customer notified via email and SMS.';
                        break;
                    case 'delivered':
                        $this->smsService->sendOrderDelivered($order);
                        $response['message'] .= ' Customer notified via email and SMS.';
                        break;
                    case 'cancelled':
                        $this->smsService->sendOrderCancelled($order);
                        $response['message'] .= ' Customer notified via email and SMS.';
                        break;
                    default:
                        $response['message'] .= ' Customer notified via email.';
                        break;
                }

            } catch (\Exception $e) {
                Log::error('Order notification error: ' . $e->getMessage());
                $response['message'] .= ' But notifications failed to send.';
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Order status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status (AJAX)
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed,refunded'
            ]);

            $order = Order::with('user')->findOrFail($id);
            $oldPaymentStatus = $order->payment_status;

            $order->update(['payment_status' => $request->payment_status]);

            $response = [
                'success' => true,
                'message' => 'Payment status updated successfully!'
            ];

            // Send notifications if payment is confirmed
            if ($request->payment_status === 'paid' && $oldPaymentStatus !== 'paid') {
                try {
                    // Send Email
                    Mail::to($order->user->email)
                        ->send(new PaymentReceived($order));

                    // Send SMS
                    $this->smsService->sendPaymentReceived($order);

                    $response['message'] = 'Payment status updated! Customer notified via email and SMS.';

                } catch (\Exception $e) {
                    Log::error('Payment notification error: ' . $e->getMessage());
                    $response['message'] = 'Payment status updated but notifications failed to send.';
                }
            }

            // Add order data to response for frontend updates
            $response['order'] = [
                'id' => $order->id,
                'payment_status' => $order->payment_status,
                'updated_at' => $order->updated_at->toIso8601String(),
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Payment status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send custom email to customer (AJAX)
     */
    public function sendEmail(Request $request, $id)
    {
        try {
            $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string|min:1'
            ]);

            $order = Order::with('user')->findOrFail($id);

            // Validate customer has email
            if (!$order->user || !$order->user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email address not available'
                ], 400);
            }

            try {
                // Send email using raw mail with proper formatting
                Mail::raw($request->message, function($msg) use ($order, $request) {
                    $msg->to($order->user->email)
                        ->subject($request->subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });

                // Log the email
                Log::info('Custom email sent successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'to' => $order->user->email,
                    'subject' => $request->subject
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully to ' . $order->user->email
                ]);
            } catch (\Exception $mailError) {
                Log::error('Mail service error: ' . $mailError->getMessage(), [
                    'order_id' => $order->id,
                    'to' => $order->user->email
                ]);

                // Check if mail is configured to log
                if (config('mail.driver') === 'log' || config('mail.driver') === null) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Email logged (mail driver: ' . (config('mail.driver') ?? 'default') . ')'
                    ]);
                }

                throw $mailError;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', array_merge($e->errors()['subject'] ?? [], $e->errors()['message'] ?? []))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Email send error: ' . $e->getMessage(), [
                'exception' => get_class($e)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please check mail configuration.'
            ], 500);
        }
    }

    /**
     * Send custom SMS to customer (AJAX)
     */
    public function sendSms(Request $request, $id)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:160'
            ]);

            $order = Order::findOrFail($id);

            // Validate phone number
            if (!$order->shipping_phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer phone number not available'
                ], 400);
            }

            $result = $this->smsService->sendCustomMessage($order, $request->message);

            if ($result['success']) {
                // Log the SMS
                Log::info('Custom SMS sent', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'to' => $order->shipping_phone,
                    'message' => $request->message
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully to ' . $order->shipping_phone
                ]);
            } else {
                Log::error('SMS service error: ' . ($result['error'] ?? 'Unknown error'));

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
        try {
            $query = Order::with('user', 'items');

            // Apply filters
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('payment_status') && $request->payment_status != 'all') {
                $query->where('payment_status', $request->payment_status);
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

                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                // Header row
                fputcsv($file, [
                    'Order Number',
                    'Customer Name',
                    'Customer Email',
                    'Date',
                    'Items Count',
                    'Subtotal',
                    'Shipping Fee',
                    'Total',
                    'Payment Method',
                    'Payment Status',
                    'Order Status',
                    'Shipping Address',
                    'Tracking Number'
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
                        $order->status,
                        $order->shipping_address,
                        $order->tracking_number ?? 'N/A'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Order export error: ' . $e->getMessage());

            return back()->with('error', 'Failed to export orders: ' . $e->getMessage());
        }
    }

    /**
     * Delete order (soft delete)
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Check if order can be deleted
            if (!in_array($order->status, ['delivered', 'cancelled'])) {
                return back()->with('error', 'Cannot delete active orders. Please cancel the order first.');
            }

            $order->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Order deletion error: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * Bulk status update
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id',
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
            ]);

            $orders = Order::with('user')->whereIn('id', $request->order_ids)->get();
            $successCount = 0;
            $notificationFailCount = 0;

            foreach ($orders as $order) {
                $oldStatus = $order->status;
                $order->update(['status' => $request->status]);
                $successCount++;

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
                        case 'cancelled':
                            $this->smsService->sendOrderCancelled($order);
                            break;
                    }
                } catch (\Exception $e) {
                    $notificationFailCount++;
                    Log::error('Bulk notification error for order ' . $order->id . ': ' . $e->getMessage());
                }
            }

            $message = $successCount . ' orders updated successfully!';
            if ($notificationFailCount > 0) {
                $message .= ' But ' . $notificationFailCount . ' notifications failed to send.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk status update error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update orders: ' . $e->getMessage());
        }
    }

    /**
     * Get order statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipped_orders' => Order::where('status', 'shipped')->count(),
                'delivered_orders' => Order::where('status', 'delivered')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
                'total_revenue' => Order::where('status', 'delivered')->where('payment_status', 'paid')->sum('total'),
                'pending_payments' => Order::where('payment_status', 'pending')->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Order statistics error: ' . $e->getMessage());

            return response()->json([], 500);
        }
    }
}
