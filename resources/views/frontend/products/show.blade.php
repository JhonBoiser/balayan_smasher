@extends('layouts.app')

@section('title', $product->name . ' - Balayan Smashers Hub')

@section('content')
<style>
    /* Product Detail Section */
    .product-detail-section {
        padding: 0 0 50px;
    }

    .product-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .breadcrumb {
        background: transparent;
        padding: 20px 0;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    .product-detail-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
    }

    /* Product Images */
    .product-images {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
    }

    .main-image {
        width: 100%;
        height: 400px;
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .image-thumbnails {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .thumbnail {
        width: 100%;
        height: 80px;
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .thumbnail.active {
        border-color: var(--primary-green);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Product Info */
    .product-info {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
        height: fit-content;
    }

    .category-badge {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 16px;
        display: inline-block;
    }

    .product-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .stars {
        color: #ffc107;
    }

    .rating-text {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .review-count {
        color: var(--primary-green);
        font-weight: 600;
    }

    .price-section {
        margin-bottom: 24px;
    }

    .current-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 8px;
    }

    .original-price {
        font-size: 1.2rem;
        color: #6c757d;
        text-decoration: line-through;
        margin-right: 12px;
    }

    .discount-badge {
        background: #f44336;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stock-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding: 16px;
        border-radius: 8px;
        background: #f8f9fa;
        border-left: 4px solid #4caf50;
    }

    .stock-info.in-stock {
        border-left-color: #4caf50;
    }

    .stock-info.low-stock {
        border-left-color: #ff9800;
    }

    .stock-info.out-of-stock {
        border-left-color: #f44336;
    }

    .stock-icon {
        font-size: 1.5rem;
        color: #4caf50;
    }

    .stock-text {
        font-weight: 600;
        color: #2c3e50;
    }

    .stock-count {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Add to Cart Section */
    .add-to-cart-section {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1px solid #e8ecef;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .quantity-label {
        font-weight: 600;
        color: #2c3e50;
        min-width: 80px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #e8ecef;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .quantity-btn:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }

    .quantity-input {
        width: 80px;
        height: 40px;
        border: 2px solid #e8ecef;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .quantity-input:focus {
        outline: none;
        border-color: var(--primary-green);
    }

    .btn-add-to-cart {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 169, 50, 0.3);
    }

    .btn-add-to-cart:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .login-prompt {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-bottom: 16px;
    }

    .login-prompt a {
        color: #2196f3;
        font-weight: 600;
        text-decoration: none;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }

    /* Product Meta */
    .product-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .meta-icon {
        color: var(--primary-green);
        font-size: 1.2rem;
    }

    .meta-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }

    .meta-value {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Product Details Tabs */
    .product-details-tabs {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
    }

    .tab-nav {
        display: flex;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 24px;
        gap: 8px;
    }

    .tab-link {
        padding: 12px 24px;
        background: none;
        border: none;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        border-radius: 8px 8px 0 0;
    }

    .tab-link.active {
        color: var(--primary-green);
        border-bottom-color: var(--primary-green);
        background: rgba(107, 169, 50, 0.05);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .description-content {
        line-height: 1.7;
        color: #555;
        font-size: 1rem;
    }

    .specifications-list {
        list-style: none;
        padding: 0;
    }

    .specifications-list li {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
    }

    .specifications-list li:last-child {
        border-bottom: none;
    }

    .spec-label {
        font-weight: 600;
        color: #2c3e50;
        min-width: 150px;
    }

    .spec-value {
        color: #555;
    }

    /* Related Products */
    .related-products {
        margin-top: 60px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 24px;
        text-align: center;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .related-product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e8ecef;
    }

    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: var(--primary-green);
    }

    .related-product-image {
        width: 100%;
        height: 180px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .related-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-product-card:hover .related-product-image img {
        transform: scale(1.05);
    }

    .related-product-info {
        padding: 20px;
    }

    .related-product-category {
        color: var(--primary-green);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .related-product-name {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }

    .related-product-price {
        font-size: 1.2rem;
        color: var(--primary-green);
        font-weight: 700;
        margin-bottom: 16px;
    }

    .btn-view-related {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        text-align: center;
    }

    .btn-view-related:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .product-detail-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .main-image {
            height: 300px;
        }

        .product-meta {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .product-detail-section {
            padding: 0 0 30px;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .current-price {
            font-size: 1.6rem;
        }

        .image-thumbnails {
            grid-template-columns: repeat(3, 1fr);
        }

        .tab-nav {
            flex-direction: column;
        }

        .tab-link {
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            border-left: 3px solid transparent;
            border-radius: 0;
        }

        .tab-link.active {
            border-left-color: var(--primary-green);
            border-bottom-color: #f0f0f0;
        }
    }

    @media (max-width: 576px) {
        .quantity-selector {
            flex-direction: column;
            align-items: flex-start;
        }

        .quantity-controls {
            width: 100%;
            justify-content: center;
        }

        .product-container {
            padding: 0 16px;
        }

        .product-images,
        .product-info,
        .product-details-tabs {
            padding: 20px;
        }
    }
</style>

<!-- Product Detail Section -->
<section class="product-detail-section">
    <div class="product-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="product-detail-layout">
            <!-- Product Images -->
            <div class="product-images">
                @if($product->images->count() > 0)
                    <div class="main-image" id="mainImage">
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" id="currentImage">
                    </div>

                    <!-- Thumbnails -->
                    <div class="image-thumbnails">
                        @foreach($product->images as $index => $image)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="main-image" id="mainImage">
                        <img src="https://via.placeholder.com/500x500?text=No+Image" alt="{{ $product->name }}" id="currentImage">
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <span class="category-badge">{{ $product->category->name }}</span>
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-text">4.5 <span class="review-count">(128 reviews)</span></span>
                </div>

                <div class="price-section">
                    @if($product->isOnSale())
                        <div class="current-price">
                            ₱{{ number_format($product->sale_price, 2) }}
                            <span class="original-price">₱{{ number_format($product->price, 2) }}</span>
                            <span class="discount-badge">Save ₱{{ number_format($product->price - $product->sale_price, 2) }}</span>
                        </div>
                    @else
                        <div class="current-price">
                            ₱{{ number_format($product->price, 2) }}
                        </div>
                    @endif
                </div>

                <div class="stock-info {{ $product->isInStock() ? ($product->isLowStock() ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                    <i class="fas {{ $product->isInStock() ? 'fa-check-circle' : 'fa-times-circle' }} stock-icon"></i>
                    <div>
                        <div class="stock-text">
                            @if($product->isInStock())
                                In Stock
                                @if($product->isLowStock())
                                    - Low Stock!
                                @endif
                            @else
                                Out of Stock
                            @endif
                        </div>
                        <div class="stock-count">
                            @if($product->isInStock())
                                {{ $product->stock }} available
                            @else
                                Currently unavailable
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Meta -->
                <div class="product-meta">
                    <div class="meta-item">
                        <i class="fas fa-barcode meta-icon"></i>
                        <div>
                            <div class="meta-label">SKU</div>
                            <div class="meta-value">{{ $product->sku }}</div>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-layer-group meta-icon"></i>
                        <div>
                            <div class="meta-label">Category</div>
                            <div class="meta-value">{{ $product->category->name }}</div>
                        </div>
                    </div>
                </div>

                <div class="add-to-cart-section">
                    @auth
                        @if($product->isInStock())
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="quantity-selector">
                                <span class="quantity-label">Quantity:</span>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                                    <input type="number" name="quantity" class="quantity-input" value="1" min="1" max="{{ $product->stock }}" id="quantityInput">
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                                </div>
                            </div>

                            <button type="submit" class="btn-add-to-cart">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                        </form>
                        @else
                            <button class="btn-add-to-cart" disabled>
                                <i class="fas fa-times-circle"></i>
                                Out of Stock
                            </button>
                        @endif
                    @else
                        <div class="login-prompt">
                            <i class="fas fa-info-circle"></i>
                            Please <a href="{{ route('login') }}">login</a> to add items to cart.
                        </div>
                        <button class="btn-add-to-cart" disabled>
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-details-tabs">
            <div class="tab-nav">
                <button class="tab-link active" onclick="openTab('description')">Description</button>
                <button class="tab-link" onclick="openTab('specifications')">Specifications</button>
                <button class="tab-link" onclick="openTab('reviews')">Reviews</button>
            </div>

            <div id="description" class="tab-content active">
                <div class="description-content">
                    <p>{{ $product->description }}</p>
                </div>
            </div>

            <div id="specifications" class="tab-content">
                @if($product->specifications)
                <div class="description-content">
                    <p>{{ $product->specifications }}</p>
                </div>
                @else
                <div class="description-content">
                    <p>No specifications available for this product.</p>
                </div>
                @endif
            </div>

            <div id="reviews" class="tab-content">
                <div class="product-rating" style="margin-bottom: 20px;">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-text">4.5 out of 5 stars (128 reviews)</span>
                </div>
                <p>Customer reviews will be displayed here.</p>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h3 class="section-title">Related Products</h3>
            <div class="related-grid">
                @foreach($relatedProducts as $related)
                <div class="related-product-card">
                    <div class="related-product-image">
                        @if($related->isOnSale())
                            <span class="product-badge sale">SALE</span>
                        @endif
                        @if($related->primaryImage)
                            <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" alt="{{ $related->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x300?text=No+Image" alt="{{ $related->name }}">
                        @endif
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-category">{{ $related->category->name }}</div>
                        <div class="related-product-name">{{ $related->name }}</div>
                        <div class="related-product-price">
                            @if($related->isOnSale())
                                ₱{{ number_format($related->sale_price, 0) }}
                            @else
                                ₱{{ number_format($related->price, 0) }}
                            @endif
                        </div>
                        <a href="{{ route('products.show', $related->slug) }}" class="btn-view-related">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<script>
// Image gallery functionality
function changeImage(src, element) {
    document.getElementById('currentImage').src = src;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

// Quantity controls
function increaseQuantity() {
    const input = document.getElementById('quantityInput');
    const max = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantityInput');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Tab functionality
function openTab(tabName) {
    // Hide all tab content
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all tab links
    document.querySelectorAll('.tab-link').forEach(link => {
        link.classList.remove('active');
    });

    // Show the specific tab content
    document.getElementById(tabName).classList.add('active');

    // Add active class to the clicked tab link
    event.currentTarget.classList.add('active');
}
</script>
@endsection
