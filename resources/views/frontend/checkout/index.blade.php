@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5>Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="shipping_name" class="form-control" required value="{{ auth()->user()->name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="shipping_email" class="form-control" required value="{{ auth()->user()->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="shipping_phone" class="form-control" required value="{{ auth()->user()->phone }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address *</label>
                                <textarea name="shipping_address" class="form-control" rows="2" required>{{ auth()->user()->address }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City *</label>
                                <input type="text" name="shipping_city" class="form-control" required value="{{ auth()->user()->city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Province *</label>
                                <input type="text" name="shipping_province" class="form-control" required value="{{ auth()->user()->province }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zip Code *</label>
                                <input type="text" name="shipping_zipcode" class="form-control" required value="{{ auth()->user()->zipcode }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label class="form-check-label" for="cod">Cash on Delivery</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="gcash" id="gcash">
                            <label class="form-check-label" for="gcash">GCash</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                            <label class="form-check-label" for="bank">Bank Transfer</label>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span>₱{{ number_format($item->getSubtotal(), 2) }}</span>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>₱{{ number_format($shippingFee, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Total:</span>
                            <span class="h5 text-primary">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
