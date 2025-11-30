<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Services\PayMongoService;
use App\Services\OrderSmsService;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PayMongoService $payMongoService;
    protected OrderSmsService $smsService;

    public function __construct(PayMongoService $payMongoService, OrderSmsService $smsService)
    {
        $this->payMongoService = $payMongoService;
        $this->smsService = $smsService;
        $this->middleware('auth');
    }

    /**
     * Display payment page for an order
     */
    public function show($orderId)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $orderId)
                ->with('info', 'This order has already been paid.');
        }

        if ($order->payment_method === 'cod') {
            $order->update(['payment_status' => 'pending']);
            return redirect()->route('orders.show', $orderId)
                ->with('success', 'Order confirmed! You will pay upon delivery.');
        }

        $publicKey = $this->payMongoService->getPublicKey();

        return view('frontend.payment.show', [
            'order' => $order,
            'publicKey' => $publicKey
        ]);
    }

    /**
     * Create payment intent
     */
    public function createIntent(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'payment_method' => 'required|in:card,gcash,grab_pay'
            ]);

            /** @var \App\Models\User $user */
            $user = auth()->user();

            $order = Order::where('id', $request->order_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'error' => 'Order already paid'
                ], 422);
            }

            $result = $this->payMongoService->createPaymentIntent([
                'amount' => $order->total,
                'order_id' => $order->id,
                'email' => $order->shipping_email,
                'name' => $order->shipping_name,
                'description' => "Order #{$order->order_number}",
                'payment_methods' => [$request->payment_method]
            ]);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }

            Log::info('Payment Intent Created', [
                'order_id' => $order->id,
                'intent_id' => $result['data']['id'],
                'amount' => $order->total
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'client_key' => $result['data']['attributes']['client_key'],
                    'intent_id' => $result['data']['id']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Create Payment Intent Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment
     */
    public function process(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'intent_id' => 'required|string',
                'payment_method_id' => 'required|string'
            ]);

            /** @var \App\Models\User $user */
            $user = auth()->user();

            $order = Order::where('id', $request->order_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'error' => 'Order already paid'
                ], 422);
            }

            // Attach payment method
            $returnUrl = route('payment.return', ['order_id' => $order->id]);
            $attachResult = $this->payMongoService->attachPaymentMethod(
                $request->intent_id,
                $request->payment_method_id,
                $returnUrl
            );

            if (!$attachResult['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $attachResult['error']
                ], 400);
            }

            // Confirm payment
            $confirmResult = $this->payMongoService->confirmPaymentIntent($request->intent_id);

            if (!$confirmResult['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $confirmResult['error']
                ], 400);
            }

            $paymentData = $confirmResult['data']['attributes'];
            $status = $paymentData['status'] ?? 'failed';

            Log::info('Payment Confirmation Result', [
                'order_id' => $order->id,
                'status' => $status
            ]);

            if ($status === 'succeeded' || $status === 'awaiting_next_action') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);

                // Send notifications
                $this->sendOrderNotifications($order, $user);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'order_id' => $order->id,
                        'status' => $status,
                        'redirect' => route('orders.show', $order->id)
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Payment failed or requires authentication'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Process Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Payment processing failed'
            ], 500);
        }
    }

    /**
     * Payment return/callback handler
     */
    public function handleReturn(Request $request, $orderId)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Check payment status
            if ($request->has('payment_intent_id')) {
                $result = $this->payMongoService->retrievePaymentIntent($request->payment_intent_id);

                if ($result['success']) {
                    $paymentData = $result['data']['attributes'];
                    $status = $paymentData['status'] ?? 'failed';

                    if ($status === 'succeeded') {
                        if ($order->payment_status !== 'paid') {
                            $order->update([
                                'payment_status' => 'paid',
                                'status' => 'processing'
                            ]);

                            $this->sendOrderNotifications($order, $user);
                        }

                        return redirect()->route('orders.show', $order->id)
                            ->with('success', 'Payment completed successfully!');
                    }
                }
            }

            return redirect()->route('orders.show', $order->id)
                ->with('info', 'Payment status is being processed. Check back soon.');

        } catch (\Exception $e) {
            Log::error('Payment Return Handler Error', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'An error occurred while processing the payment.');
        }
    }

    /**
     * Payment failure handler
     */
    public function handleFailure(Request $request, $orderId)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            Log::warning('Payment Failed', [
                'order_id' => $order->id,
                'reason' => $request->get('reason', 'Unknown')
            ]);

            return redirect()->route('payment.show', $order->id)
                ->with('error', 'Payment failed. Please try again.')
                ->with('reason', $request->get('reason'));

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'An error occurred.');
        }
    }

    /**
     * Webhook handler for PayMongo events
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->json()->all();

            Log::info('PayMongo Webhook Received', [
                'payload' => $payload
            ]);

            $result = $this->payMongoService->processWebhook($payload);

            return response()->json([
                'success' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook Processing Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Send order notifications
     */
    protected function sendOrderNotifications($order, $user)
    {
        // Send email
        try {
            Mail::to($user->email)->send(new OrderPlaced($order));
            Log::info('Order confirmation email sent', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send order email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        // Send SMS
        try {
            $smsResult = $this->smsService->sendOrderPlaced($order);
            if ($smsResult['success']) {
                Log::info('Order confirmation SMS sent', ['order_id' => $order->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order SMS', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
