<?php
/*
============================================
CART VIEW
resources/views/frontend/cart/index.blade.php
============================================
*/
?>
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-cart3"></i> Shopping Cart</h2>

    @if($cartItems->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center border-bottom py-3">
                        <div class="col-md-2">
                            @if($item->product->primaryImage)
                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                     class="img-fluid rounded" alt="{{ $item->product->name }}">
                            @else
                                <img src="https://tse2.mm.bing.net/th/id/OIP.Em_MJNuvUgNU33oSE66ReQHaHa?pid=Api&P=0&h=180" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6>{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">{{ $item->product->category->name }}</p>
                        </div>
                        <div class="col-md-2">
                            <p class="fw-bold mb-0">₱{{ number_format($item->product->getCurrentPrice(), 2) }}</p>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" class="form-control form-control-sm"
                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                       onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">₱{{ number_format($item->getSubtotal(), 2) }}</span>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-bold">₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="fw-bold">₱{{ number_format($shippingFee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h5">Total:</span>
                        <span class="h5 text-primary">₱{{ number_format($total, 2) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
        <h3 class="mt-3">Your cart is empty</h3>
        <p class="text-muted">Start shopping to add items to your cart!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Browse Products</a>
    </div>
    @endif
</div>
@endsection
