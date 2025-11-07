<?php
/*
============================================
PRODUCT DETAIL VIEW
resources/views/frontend/products/show.blade.php
============================================
*/
?>
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                @if($product->images->count() > 0)
                    <div id="productCarousel" class="carousel slide">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     class="d-block w-100"
                                     style="height: 500px; object-fit: contain;">
                            </div>
                            @endforeach
                        </div>
                        @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>
                @else
                    <img src="https://tse2.mm.bing.net/th/id/OIP.Em_MJNuvUgNU33oSE66ReQHaHa?pid=Api&P=0&h=180" class="card-img-top">
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
            <h1 class="mb-3">{{ $product->name }}</h1>

            <div class="mb-3">
                @if($product->isOnSale())
                    <h2 class="text-danger mb-0">₱{{ number_format($product->sale_price, 2) }}</h2>
                    <p class="text-muted text-decoration-line-through">₱{{ number_format($product->price, 2) }}</p>
                    <span class="badge bg-danger">Save ₱{{ number_format($product->price - $product->sale_price, 2) }}</span>
                @else
                    <h2 class="text-primary">₱{{ number_format($product->price, 2) }}</h2>
                @endif
            </div>

            <div class="mb-4">
                <span class="badge {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }} fs-6">
                    @if($product->isInStock())
                        <i class="bi bi-check-circle"></i> In Stock ({{ $product->stock }} available)
                        @if($product->isLowStock())
                            <small class="text-warning"> - Low Stock!</small>
                        @endif
                    @else
                        <i class="bi bi-x-circle"></i> Out of Stock
                    @endif
                </span>
            </div>

            @auth
                @if($product->isInStock())
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row g-3 mb-4">
                        <div class="col-auto">
                            <label class="form-label">Quantity:</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" style="width: 100px;">
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Please <a href="{{ route('login') }}">login</a> to add items to cart.
                </div>
            @endauth

            <hr>

            <div class="mb-4">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>

            @if($product->specifications)
            <div class="mb-4">
                <h5>Specifications</h5>
                <p class="text-muted">{{ $product->specifications }}</p>
            </div>
            @endif

            <div>
                <h6>SKU: {{ $product->sku }}</h6>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-md-3">
                <div class="card h-100 product-card shadow-sm">
                    @if($related->primaryImage)
                        <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" class="card-img-top product-image" alt="{{ $related->name }}">
                    @else
                        <img src="https://tse2.mm.bing.net/th/id/OIP.Em_MJNuvUgNU33oSE66ReQHaHa?pid=Api&P=0&h=180" class="card-img-top product-image" alt="{{ $related->name }}">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $related->name }}</h6>
                        <p class="text-primary fw-bold">₱{{ number_format($related->getCurrentPrice(), 2) }}</p>
                        <a href="{{ route('products.show', $related->slug) }}" class="btn btn-primary btn-sm w-100">View</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
