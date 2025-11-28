{{-- resources/views/admin/search/advanced.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Advanced Search')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-funnel"></i> Advanced Search</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-house"></i> Back to Dashboard
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Filter Options</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.search.advanced') }}" id="advanced-search-form">
                    <div class="mb-3">
                        <label for="q" class="form-label">Search Term</label>
                        <input type="text"
                               class="form-control"
                               id="q"
                               name="q"
                               placeholder="Search..."
                               value="{{ request('q') }}">
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Search Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="products" {{ request('type') === 'products' ? 'selected' : '' }}>Products</option>
                            <option value="orders" {{ request('type') === 'orders' ? 'selected' : '' }}>Orders</option>
                            <option value="customers" {{ request('type') === 'customers' ? 'selected' : '' }}>Customers</option>
                            <option value="categories" {{ request('type') === 'categories' ? 'selected' : '' }}>Categories</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="order_status" class="form-label">Order Status</label>
                        <select class="form-select" id="order_status" name="order_status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price_from" class="form-label">Price From (₱)</label>
                        <input type="number"
                               class="form-control"
                               id="price_from"
                               name="price_from"
                               min="0"
                               step="0.01"
                               value="{{ request('price_from') }}">
                    </div>

                    <div class="mb-3">
                        <label for="price_to" class="form-label">Price To (₱)</label>
                        <input type="number"
                               class="form-control"
                               id="price_to"
                               name="price_to"
                               min="0"
                               step="0.01"
                               value="{{ request('price_to') }}">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <a href="{{ route('admin.search.advanced') }}" class="btn btn-outline-secondary">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Search Results</h5>
            </div>
            <div class="card-body">
                @if(request()->filled('q') || request()->filled('type'))
                    @if(isset($results))
                        @php
                            $total = 0;
                            foreach($results as $type => $items) {
                                if (is_array($items)) {
                                    $total += count($items);
                                }
                            }
                        @endphp

                        @if($total > 0)
                            <p class="text-muted mb-3">Found {{ $total }} result(s)</p>

                            <!-- Products Results -->
                            @if(isset($results['products']) && count($results['products']) > 0)
                            <div class="mb-4">
                                <h6 class="mb-2"><i class="bi bi-box"></i> Products ({{ count($results['products']) }})</h6>
                                <div class="list-group list-group-sm">
                                    @foreach($results['products'] as $product)
                                    <a href="{{ $product['url'] }}" class="list-group-item list-group-item-action">
                                        {{ $product['name'] }} - {{ $product['price'] }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Orders Results -->
                            @if(isset($results['orders']) && count($results['orders']) > 0)
                            <div class="mb-4">
                                <h6 class="mb-2"><i class="bi bi-receipt"></i> Orders ({{ count($results['orders']) }})</h6>
                                <div class="list-group list-group-sm">
                                    @foreach($results['orders'] as $order)
                                    <a href="{{ $order['url'] }}" class="list-group-item list-group-item-action">
                                        {{ $order['number'] }} - {{ $order['customer'] }} ({{ $order['amount'] }})
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Customers Results -->
                            @if(isset($results['customers']) && count($results['customers']) > 0)
                            <div class="mb-4">
                                <h6 class="mb-2"><i class="bi bi-person"></i> Customers ({{ count($results['customers']) }})</h6>
                                <div class="list-group list-group-sm">
                                    @foreach($results['customers'] as $customer)
                                    <a href="{{ $customer['url'] }}" class="list-group-item list-group-item-action">
                                        {{ $customer['name'] }} - {{ $customer['email'] }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Categories Results -->
                            @if(isset($results['categories']) && count($results['categories']) > 0)
                            <div class="mb-4">
                                <h6 class="mb-2"><i class="bi bi-tag"></i> Categories ({{ count($results['categories']) }})</h6>
                                <div class="list-group list-group-sm">
                                    @foreach($results['categories'] as $category)
                                    <a href="{{ $category['url'] }}" class="list-group-item list-group-item-action">
                                        {{ $category['name'] }} ({{ $category['products'] }} products)
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No results found matching your criteria
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Use the filters on the left to search
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
