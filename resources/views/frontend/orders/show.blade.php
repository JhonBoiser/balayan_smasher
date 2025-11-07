@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->order_number }}</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="row align-items-center border-bottom py-3">
                        <div class="col-md-2">
                            @if($item->product->primaryImage)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" class="img-fluid rounded">
                            @else
                            <img src="https://via.placeholder.com/80" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-5">
                            <h6>{{ $item->product_name }}</h6>
                        </div>
                        <div class="col-md-2 text-center">
                            <span>Qty: {{ $item->quantity }}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <strong>₱{{ number_format($item->subtotal, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Status:</span>
                        <span class="badge bg-{{ $order->getStatusBadgeClass() }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment:</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>₱{{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="h5">Total:</span>
                        <span class="h5 text-primary">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                    <p class="mb-1">{{ $order->shipping_phone }}</p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_zipcode }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
