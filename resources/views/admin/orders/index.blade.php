{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Orders Management')

@section('styles')
<style>
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        .mobile-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .mobile-card-body {
            font-size: 0.9rem;
        }
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .badge-group {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-bag-check"></i> Orders</h2>
</div>

<!-- Filter Tabs - Scrollable on Mobile -->
<div class="mb-3" style="overflow-x: auto; white-space: nowrap;">
    <ul class="nav nav-tabs" style="flex-wrap: nowrap;">
        <li class="nav-item">
            <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                All <span class="badge bg-secondary ms-1">{{ \App\Models\Order::count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">
                Pending <span class="badge bg-warning text-dark ms-1">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'processing' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">
                Processing <span class="badge bg-info ms-1">{{ \App\Models\Order::where('status', 'processing')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'shipped' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">
                Shipped <span class="badge bg-primary ms-1">{{ \App\Models\Order::where('status', 'shipped')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'delivered' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'delivered']) }}">
                Delivered <span class="badge bg-success ms-1">{{ \App\Models\Order::where('status', 'delivered')->count() }}</span>
            </a>
        </li>
    </ul>
</div>

<!-- Desktop View -->
<div class="card shadow-sm d-none d-lg-block">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-bold">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $order->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->items->count() }} items</span>
                        </td>
                        <td>
                            <strong>₱{{ number_format($order->total, 2) }}</strong>
                        </td>
                        <td>
                            <div>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                <br>
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="btn btn-sm btn-primary"
                               title="View Details">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No orders yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Mobile View -->
<div class="d-lg-none">
    @forelse($orders as $order)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <div>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-bold h6 mb-0">
                    {{ $order->order_number }}
                </a>
                <br>
                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
            </div>
            <div>
                <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="mobile-card-body">
            <div class="mobile-card-row">
                <span class="text-muted">Customer:</span>
                <strong>{{ $order->user->name }}</strong>
            </div>

            <div class="mobile-card-row">
                <span class="text-muted">Items:</span>
                <span class="badge bg-secondary">{{ $order->items->count() }} items</span>
            </div>

            <div class="mobile-card-row">
                <span class="text-muted">Total:</span>
                <strong class="text-primary">₱{{ number_format($order->total, 2) }}</strong>
            </div>

            <div class="mobile-card-row">
                <span class="text-muted">Payment:</span>
                <div class="badge-group">
                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-eye"></i> View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">No orders yet</p>
    </div>
    @endforelse

    @if($orders->hasPages())
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
