@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('content')
<style>
    /* Page Header */
    .shop-header {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        padding: 45px 0;
        position: relative;
        overflow: hidden;
    }

    .shop-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="25" fill="rgba(255,255,255,0.05)"/></svg>');
        background-size: 60px 60px;
        opacity: 0.3;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .header-content h1 {
        color: white;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .header-content p {
        color: rgba(255,255,255,0.95);
        font-size: 1rem;
    }

    /* Main Shop Section */
    .shop-section {
        padding: 45px 0;
        background: #f8f9fa;
    }

    .shop-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .shop-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
    }

    /* Sidebar */
    .shop-sidebar {
        background: white;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        height: fit-content;
        position: sticky;
        top: 90px;
    }

    .sidebar-section {
        margin-bottom: 28px;
    }

    .sidebar-section:last-child {
        margin-bottom: 0;
    }

    .sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-title i {
        color: #6ba932;
        font-size: 1rem;
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 24px;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    input::placeholder {
        font-size: 17px;
    }

    .search-input:focus {
        outline: none;
        border-color: #6ba932;
        box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 14px;
    }

    /* Category List */
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-item {
        padding: 10px 12px;
        margin-bottom: 6px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #555;
        text-decoration: none;
    }

    .category-item:hover,
    .category-item.active {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
    }

    .category-item i {
        font-size: 16px;
    }

    /* Price Range - Fixed Responsive Styles */
    .price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }

    .price-input {
        padding: 8px 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.85rem;
        text-align: center;
        width: 100%;
        min-width: 0; /* Prevents overflow on small screens */
        box-sizing: border-box; /* Ensures padding doesn't break layout */
    }

    .price-input:focus {
        outline: none;
        border-color: #6ba932;
    }

    /* Hide number input spinners for better mobile experience */
    .price-input::-webkit-outer-spin-button,
    .price-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .price-input[type=number] {
        -moz-appearance: textfield;
    }

    .apply-filter-btn {
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .apply-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
    }

    /* Main Content */
    .shop-main {
        background: white;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    /* Toolbar */
    .shop-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 12px;
    }

    .results-info {
        color: #666;
        font-size: 0.9rem;
    }

    .results-info strong {
        color: #6ba932;
        font-weight: 700;
    }

    .sort-select {
        padding: 8px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sort-select:focus {
        outline: none;
        border-color: #6ba932;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        border-color: #6ba932;
    }

    .product-image {
        width: 100%;
        height: 180px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-badge.sale {
        background: #f44336;
    }

    .product-badge.new {
        background: #4caf50;
    }

    .product-badge.popular {
        background: #ff9800;
    }

    .product-badge.premium {
        background: #9c27b0;
    }

    .product-badge.official {
        background: #2196f3;
    }

    .product-badge.professional {
        background: #f44336;
    }

    .product-info {
        padding: 14px;
    }

    .product-category {
        color: #6ba932;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .product-name {
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }

    .product-colors {
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 8px;
    }

    .stars {
        color: #ffc107;
        font-size: 0.75rem;
    }

    .rating-text {
        color: #999;
        font-size: 0.75rem;
    }

    .product-price {
        font-size: 1.2rem;
        color: #6ba932;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .price-old {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9rem;
        margin-left: 6px;
        font-weight: 400;
    }

    .btn-add-cart {
        width: 100%;
        padding: 9px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
    }

    .btn-view-details {
        width: 100%;
        padding: 9px;
        background: #6ba932;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
    }

    .btn-view-details:hover {
        background: #5a9028;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .stock-badge.in-stock {
        background: #4caf50;
    }

    .stock-badge.out-of-stock {
        background: #f44336;
    }

    .stock-badge.low-stock {
        background: #ff9800;
    }

    /* Mobile Toggle */
    .filter-toggle {
        display: none;
        width: 100%;
        padding: 12px;
        background: #6ba932;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 20px;
        gap: 8px;
        align-items: center;
        justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .shop-layout {
            grid-template-columns: 1fr;
        }

        .shop-sidebar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            background: white;
            border-radius: 0;
            padding: 20px;
            overflow-y: auto;
        }

        .shop-sidebar.active {
            display: block;
        }

        .filter-toggle {
            display: flex;
        }

        .close-filters {
            display: block;
            width: 100%;
            padding: 10px;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 20px;
            cursor: pointer;
        }
    }

    @media (max-width: 768px) {
        .shop-header {
            padding: 32px 0;
        }

        .header-content h1 {
            font-size: 1.8rem;
        }

        .shop-section {
            padding: 32px 0;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
        }

        .product-image {
            height: 140px;
        }
    }

    @media (max-width: 576px) {
        .header-content h1 {
            font-size: 1.6rem;
        }

        .shop-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .products-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        /* Mobile-specific price range improvements */
        .price-inputs {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .price-input {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 12px 10px; /* Larger touch targets */
        }

        .apply-filter-btn {
            padding: 12px;
            font-size: 1rem;
        }
    }

    @media (max-width: 400px) {
        .shop-container {
            padding: 0 16px;
        }

        .shop-main {
            padding: 16px;
        }

        .price-input {
            padding: 10px 8px;
            font-size: 14px;
        }
    }
</style>

<!-- Shop Header -->
<section class="shop-header">
    <div class="header-content">
        <h1>Shop All Products</h1>
        <p>Find the perfect sports equipment for your game</p>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section">
    <div class="shop-container">

        <!-- Mobile Filter Toggle -->
        <button class="filter-toggle" onclick="toggleFilters()">
            <i class="fas fa-filter"></i>
            Show Filters
        </button>

        <div class="shop-layout">
            <!-- Sidebar -->
            <aside class="shop-sidebar" id="shopSidebar">
                <button class="close-filters" onclick="toggleFilters()" style="display: none;">
                    <i class="fas fa-times"></i> Close Filters
                </button>

                <!-- Search -->
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Search products:" id="searchInput">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <!-- Categories -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">
                        <i class="fas fa-th-large"></i>
                        Categories
                    </h3>
                    <ul class="category-list">
                        <a href="{{ route('products.index') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                            <i class="fas fa-border-all"></i>
                            All Products
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                           class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                            <i class="fas fa-{{ $category->icon ?? 'tag' }}"></i>
                            {{ $category->name }} ({{ $category->products()->where('is_active', true)->count() }})
                        </a>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">
                        <i class="fas fa-tag"></i>
                        Price Range
                    </h3>
                    <div class="price-inputs">
                        <input type="number" class="price-input" placeholder="Min ₱" min="0" id="minPrice">
                        <input type="number" class="price-input" placeholder="Max ₱" min="0" id="maxPrice">
                    </div>
                    <button class="apply-filter-btn" id="applyPriceFilter">Apply Filter</button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="shop-main">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="results-info">
                        Showing <strong>{{ $products->count() }}</strong> products
                    </div>
                    <form method="GET" class="sort-form">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <select class="sort-select" name="sort" onchange="this.form.submit()">
                            <option value="">Sort by: Featured</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </form>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @forelse($products as $product)
                    <div class="product-card" data-price="{{ $product->getCurrentPrice() }}" data-name="{{ strtolower($product->name) }}">
                        <div class="product-image">
                            <!-- Product Badges -->
                            @if($product->isOnSale())
                                <span class="product-badge sale">SALE</span>
                            @endif

                            <!-- Stock Badge -->
                            <span class="stock-badge {{ $product->isInStock() ? ($product->isLowStock() ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                                {{ $product->isInStock() ? ($product->isLowStock() ? 'Low Stock' : 'In Stock') : 'Out of Stock' }}
                            </span>

                            <!-- Fixed Product Image -->
                            <img src="{{ $product->getDisplayImageUrl() }}"
                                 alt="{{ $product->name }}"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        </div>

                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name }}</div>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-colors">{{ Str::limit($product->description, 60) }}</div>

                            <div class="product-rating">
                                <div class="stars">
                                    <!-- Static rating for demo - you can replace with actual ratings -->
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-text">(4.5)</span>
                            </div>

                            <div class="product-price">
                                @if($product->isOnSale())
                                    ₱{{ number_format($product->sale_price, 0) }}
                                    <span class="price-old">₱{{ number_format($product->price, 0) }}</span>
                                @else
                                    ₱{{ number_format($product->price, 0) }}
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}" class="btn-view-details">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <i class="fas fa-search" style="font-size: 48px; color: #ddd; margin-bottom: 16px;"></i>
                        <h3 style="color: #666; margin-bottom: 8px;">No products found</h3>
                        <p style="color: #999;">Try adjusting your filters or search terms</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination Section REMOVED -->
            </main>
        </div>
    </div>
</section>

<script>
function toggleFilters() {
    const sidebar = document.getElementById('shopSidebar');
    const closeBtn = sidebar.querySelector('.close-filters');

    sidebar.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
        closeBtn.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        closeBtn.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const productName = card.getAttribute('data-name');
        if (productName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Price filter functionality
document.getElementById('applyPriceFilter').addEventListener('click', function() {
    const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const price = parseFloat(card.getAttribute('data-price'));
        if (price >= minPrice && price <= maxPrice) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Close filters when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('shopSidebar');
    const filterToggle = document.querySelector('.filter-toggle');

    if (window.innerWidth <= 992 && sidebar.classList.contains('active') &&
        !sidebar.contains(event.target) && !filterToggle.contains(event.target)) {
        toggleFilters();
    }
});

// Enhanced price input validation
document.addEventListener('DOMContentLoaded', function() {
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');

    // Validate price inputs
    [minPriceInput, maxPriceInput].forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && parseFloat(this.value) < 0) {
                this.value = 0;
            }
        });

        // Prevent negative numbers
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'e' || e.key === 'E') {
                e.preventDefault();
            }
        });
    });

    // Add image error handling for all product images
    const productImages = document.querySelectorAll('.product-image img');
    productImages.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'https://via.placeholder.com/300x300?text=No+Image';
            this.style.objectFit = 'contain';
            this.parentElement.style.background = '#f8f9fa';
        });
    });
});

// Enhanced image loading with lazy loading
document.addEventListener('DOMContentLoaded', function() {
    // Add lazy loading to all product images
    const productImages = document.querySelectorAll('.product-image img');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src') || img.src;
                imageObserver.unobserve(img);
            }
        });
    });

    productImages.forEach(img => {
        // Store original src in data-src for lazy loading
        if (!img.getAttribute('data-src')) {
            img.setAttribute('data-src', img.src);
        }
        imageObserver.observe(img);
    });
});
</script>
@endsection
