<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product.primaryImage')
            ->where('user_id', auth()->id())
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        $shippingFee = 100; // Fixed shipping fee
        $total = $subtotal + $shippingFee;

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', auth())
            ->where('id', $id)
            ->firstOrFail();

        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        Cart::where('user_id', auth())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        Cart::where('user_id', auth())->delete();
        return back()->with('success', 'Cart cleared!');
    }
}
