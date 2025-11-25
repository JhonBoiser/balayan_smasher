<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderSmsService;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected OrderSmsService $smsService;

    public function __construct(OrderSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $cartItems = Cart::with('product.primaryImage')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->getSubtotal());
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    /**
     * Process the checkout and create order
     */
    public function process(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        Log::info('Checkout process started', [
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_province' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'payment_method' => 'required|in:cod,gcash,card,paymaya,bank_transfer',
            'notes' => 'nullable|string|max:500'
        ]);

        Log::info('Validation passed', ['payment_method' => $request->payment_method]);

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty during checkout', ['user_id' => $user->id]);
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->getCurrentPrice() * $item->quantity);
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;

        DB::beginTransaction();

        try {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_province' => $request->shipping_province,
                'shipping_zipcode' => $request->shipping_zipcode,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->getCurrentPrice(),
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->getCurrentPrice() * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            Log::info('Order items created and stock updated', ['order_id' => $order->id]);

            Cart::where('user_id', $user->id)->delete();
            Log::info('Cart cleared', ['user_id' => $user->id]);

            DB::commit();
            Log::info('Transaction committed successfully', ['order_id' => $order->id]);

            // Send notifications
            try {
                Mail::to($user->email)->send(new OrderPlaced($order));
                Log::info('Order confirmation email sent', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send order email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            try {
                $smsResult = $this->smsService->sendOrderPlaced($order);
                if ($smsResult['success']) {
                    Log::info('Order confirmation SMS sent', ['order_id' => $order->id]);
                } else {
                    Log::warning('Failed to send order SMS', [
                        'order_id' => $order->id,
                        'error' => $smsResult['error'] ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('SMS service exception', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Redirecting to homepage after successful order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return redirect()->route('home')
                ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number . '. We will process your order shortly.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Checkout process failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Failed to process order: ' . $e->getMessage())
                ->withInput();
        }
    }
}
