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

        // Check if product is in stock
        if (!$product->isInStock()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock.'
                ], 422);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        // Check if requested quantity exceeds available stock
        if ($product->stock < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available. Only ' . $product->stock . ' items left.'
                ], 422);
            }
            return back()->with('error', 'Insufficient stock available. Only ' . $product->stock . ' items left.');
        }

        // Find existing cart item or create new one
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Check if total quantity exceeds stock
            if ($product->stock < $newQuantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. You already have ' . $cartItem->quantity . ' in cart, only ' . $product->stock . ' available.'
                    ], 422);
                }
                return back()->with('error', 'Cannot add more items. You already have ' . $cartItem->quantity . ' in cart, only ' . $product->stock . ' available.');
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        // Get updated cart count
        $cartCount = Cart::where('user_id', auth()->id())->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount,
                'product' => [
                    'name' => $product->name,
                    'price' => $product->isOnSale() ? $product->sale_price : $product->price,
                    'image' => $product->getDisplayImageUrl()
                ]
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;

        // Check if product is in stock
        if (!$product->isInStock()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock.'
                ], 422);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        // Check if requested quantity exceeds available stock
        if ($product->stock < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available. Only ' . $product->stock . ' items left.'
                ], 422);
            }
            return back()->with('error', 'Insufficient stock available. Only ' . $product->stock . ' items left.');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Recalculate totals
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;
        $cartCount = $cartItems->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => $cartCount,
                'totals' => [
                    'subtotal' => number_format($subtotal, 2),
                    'shipping_fee' => number_format($shippingFee, 2),
                    'total' => number_format($total, 2)
                ],
                'item_subtotal' => number_format($cartItem->getSubtotal(), 2)
            ]);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        // Recalculate totals after removal
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;
        $cartCount = $cartItems->count();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => $cartCount,
                'totals' => [
                    'subtotal' => number_format($subtotal, 2),
                    'shipping_fee' => number_format($shippingFee, 2),
                    'total' => number_format($total, 2)
                ]
            ]);
        }

        return back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0,
                'totals' => [
                    'subtotal' => '0.00',
                    'shipping_fee' => '0.00',
                    'total' => '0.00'
                ]
            ]);
        }

        return back()->with('success', 'Cart cleared!');
    }

    /**
     * Get cart summary for header/navigation
     */
    public function getCartSummary()
    {
        $cartCount = Cart::where('user_id', auth()->id())->count();
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        return response()->json([
            'cart_count' => $cartCount,
            'subtotal' => number_format($subtotal, 2)
        ]);
    }

    /**
     * Get cart items for mini-cart display
     */
    public function getCartItems()
    {
        $cartItems = Cart::with('product.primaryImage')
            ->where('user_id', auth()->id())
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_slug' => $item->product->slug,
                    'product_price' => $item->product->isOnSale() ? $item->product->sale_price : $item->product->price,
                    'product_image' => $item->product->getDisplayImageUrl(),
                    'quantity' => $item->quantity,
                    'subtotal' => $item->getSubtotal(),
                    'max_stock' => $item->product->stock
                ];
            });

        $subtotal = $cartItems->sum('subtotal');
        $shippingFee = 100;
        $total = $subtotal + $shippingFee;

        return response()->json([
            'cart_items' => $cartItems,
            'summary' => [
                'subtotal' => number_format($subtotal, 2),
                'shipping_fee' => number_format($shippingFee, 2),
                'total' => number_format($total, 2)
            ]
        ]);
    }
}
