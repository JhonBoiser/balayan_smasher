@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-bag-check"></i> My Orders</h2>

    @forelse($orders as $order)
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h6 class="mb-1">Order #{{ $order->order_number }}</h6>
                    <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                </div>
                <div class="col-md-2">
                    <span class="badge bg-{{ $order->getStatusBadgeClass() }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="col-md-2">
                    <span class="badge bg-info">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="col-md-2">
                    <strong>₱{{ number_format($order->total, 2) }}</strong>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3">No orders yet</h4>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Start Shopping</a>
    </div>
    @endforelse

    {{ $orders->links() }}
</div>
@endsection
