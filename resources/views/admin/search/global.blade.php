{{-- resources/views/admin/search/global.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Global Search')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-search"></i> Global Search</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-house"></i> Back to Dashboard
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Search All Items</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.search.global') }}" class="mb-4">
            <div class="input-group input-group-lg">
                <input type="text"
                       class="form-control"
                       name="q"
                       placeholder="Search products, orders, customers, categories..."
                       value="{{ request('q') }}"
                       required>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('admin.search.advanced') }}" class="btn btn-outline-secondary">
                    Advanced Search
                </a>
            </div>
            <small class="text-muted">Search by name, email, SKU, order number, or phone</small>
        </form>

        @if(request('q'))
            @if(isset($results))
                <!-- Products Results -->
                @if(isset($results['products']) && count($results['products']) > 0)
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-box"></i> Products ({{ count($results['products']) }})</h5>
                    <div class="list-group">
                        @foreach($results['products'] as $product)
                        <a href="{{ $product['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $product['name'] }}</h6>
                                    <small class="text-muted">SKU: {{ $product['sku'] }} | Category: {{ $product['category'] }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $product['price'] }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Orders Results -->
                @if(isset($results['orders']) && count($results['orders']) > 0)
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-receipt"></i> Orders ({{ count($results['orders']) }})</h5>
                    <div class="list-group">
                        @foreach($results['orders'] as $order)
                        <a href="{{ $order['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $order['number'] }}</h6>
                                    <small class="text-muted">Customer: {{ $order['customer'] }} | Date: {{ $order['created_at'] ?? 'N/A' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info">{{ $order['amount'] }}</span>
                                    <span class="badge {{ $order['status_badge'] }}">{{ ucfirst($order['status']) }}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Customers Results -->
                @if(isset($results['customers']) && count($results['customers']) > 0)
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-person"></i> Customers ({{ count($results['customers']) }})</h5>
                    <div class="list-group">
                        @foreach($results['customers'] as $customer)
                        <a href="{{ $customer['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $customer['name'] }}</h6>
                                    <small class="text-muted">{{ $customer['email'] }} | Phone: {{ $customer['phone'] }}</small>
                                </div>
                                <span class="badge bg-success">{{ $customer['orders'] }} Orders</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Categories Results -->
                @if(isset($results['categories']) && count($results['categories']) > 0)
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-tag"></i> Categories ({{ count($results['categories']) }})</h5>
                    <div class="list-group">
                        @foreach($results['categories'] as $category)
                        <a href="{{ $category['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $category['name'] }}</h6>
                                    <small class="text-muted">{{ $category['description'] ?? 'No description' }}</small>
                                </div>
                                <span class="badge bg-warning text-dark">{{ $category['products'] }} Products</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!isset($results) || (empty($results['products']) && empty($results['orders']) && empty($results['customers']) && empty($results['categories'])))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No results found for "{{ request('q') }}"
                </div>
                @endif
            @endif
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Enter a search term to find products, orders, customers, or categories
            </div>
        @endif
    </div>
</div>
@endsection
