<?php
/*
============================================
PRODUCTS INDEX VIEW (with Accordion Filter)
resources/views/frontend/products/index.blade.php
============================================
*/
?>
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar (Accordion Filters) -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="filterAccordion">

                        <!-- Categories Accordion -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCategories">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                                    Categories
                                </button>
                            </h2>
                            <div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#filterAccordion">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('products.index') }}"
                                           class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                            All Products
                                        </a>
                                        @foreach($categories as $category)
                                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                           class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                                            {{ $category->name }} ({{ $category->products()->where('is_active', true)->count() }})
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Accordion Example (Optional for future filters)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPrice">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="false" aria-controls="collapsePrice">
                                    Price Range
                                </button>
                            </h2>
                            <div id="collapsePrice" class="accordion-collapse collapse" aria-labelledby="headingPrice" data-bs-parent="#filterAccordion">
                                <div class="accordion-body">
                                    <input type="range" class="form-range" min="0" max="10000" step="100">
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Products</h2>
                    <p class="text-muted mb-0">{{ $products->total() }} products found</p>
                </div>
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="">Sort By</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 product-card shadow-sm position-relative">
                        @if($product->isOnSale())
                            <span class="badge badge-sale position-absolute top-0 start-0 m-2 bg-danger">SALE</span>
                        @endif

                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                 class="card-img-top product-image"
                                 alt="{{ $product->name }}">
                        @else
                            <img src=https://tse2.mm.bing.net/th/id/OIP.Em_MJNuvUgNU33oSE66ReQHaHa?pid=Api&P=0&h=180"
                                 class="card-img-top product-image"
                                 alt="{{ $product->name }}">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2 align-self-start">{{ $product->category->name }}</span>
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        @if($product->isOnSale())
                                            <span class="text-danger fw-bold">₱{{ number_format($product->sale_price, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through d-block">₱{{ number_format($product->price, 2) }}</small>
                                        @else
                                            <span class="fw-bold">₱{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <span class="badge {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->isInStock() ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">No products found</h4>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
