<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderPlaced;
use App\Services\OrderSmsService;

class CheckoutController extends Controller
{
    protected $smsService;

    public function __construct(OrderSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $cartItems = Cart::with('product.primaryImage')
            ->where('user_id', auth()->id())
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

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_province' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'payment_method' => 'required|in:cod,gcash,bank_transfer',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->getSubtotal());
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_province' => $request->shipping_province,
                'shipping_zipcode' => $request->shipping_zipcode,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->getCurrentPrice(),
                    'quantity' => $item->quantity,
                    'subtotal' => $item->getSubtotal(),
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // ✅ Send SMS notification
            $this->smsService->sendOrderPlaced($order);

            // ✅ Send confirmation email
            Mail::to($order->shipping_email)->send(new OrderPlaced($order));

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully! Check your SMS and email.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}
