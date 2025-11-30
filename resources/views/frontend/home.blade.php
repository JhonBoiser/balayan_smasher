@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Balayan Smashers Hub - Quality Sports Equipment')

@section('content')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, rgba(107, 169, 50, 0.95) 0%, rgba(90, 144, 40, 0.95) 100%),
                    url('https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=1200&h=800&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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

    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }

    .hero-text {
        max-width: 600px;
        animation: fadeInLeft 0.8s ease;
    }

    .hero-text h1 {
        color: white;
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 16px;
        line-height: 1.2;
        text-shadow: 0 3px 10px rgba(0,0,0,0.3);
    }

    .hero-text p {
        color: rgba(255,255,255,0.95);
        font-size: 1.1rem;
        margin-bottom: 28px;
        line-height: 1.7;
    }

    .hero-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-hero {
        padding: 13px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary {
        background: white;
        color: #6ba932;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn-primary:hover {
        background: #f8f9fa;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        color: #6ba932;
        text-decoration: none;
    }

    .btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-secondary:hover {
        background: white;
        color: #6ba932;
        transform: translateY(-3px);
        text-decoration: none;
    }

    /* Categories Section */
    .categories-section {
        padding: 50px 0;
        background: #f8f9fa;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .section-header p {
        color: #666;
        font-size: 1rem;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    .category-card {
        background: white;
        border-radius: 14px;
        padding: 28px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        border: 2px solid transparent;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }

    .category-card:hover {
        transform: translateY(-8px);
        border-color: #6ba932;
        box-shadow: 0 8px 25px rgba(107, 169, 50, 0.15);
        text-decoration: none;
    }

    .category-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .category-icon i {
        font-size: 32px;
        color: white;
    }

    .category-card h3 {
        font-size: 1.15rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .category-card p {
        color: #666;
        font-size: 0.88rem;
        margin: 0;
    }

    /* Featured Products Section */
    .featured-section {
        padding: 50px 0;
        background: white;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #6ba932;
    }

    .product-image {
        width: 100%;
        height: 200px;
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
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 2;
    }

    .product-badge.sale {
        background: #f44336;
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

    .product-info {
        padding: 16px;
    }

    .product-category {
        color: #6ba932;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .product-name {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }

    .product-price {
        font-size: 1.3rem;
        color: #6ba932;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .price-old {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
        margin-left: 6px;
        font-weight: 400;
    }

    .btn-view-details {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        text-align: center;
    }

    .btn-view-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Features Section */
    .features-section {
        padding: 50px 0;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 28px;
    }

    .feature-item {
        text-align: center;
        padding: 20px;
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .feature-icon i {
        font-size: 28px;
        color: white;
    }

    .feature-item h3 {
        font-size: 1.1rem;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .feature-item p {
        font-size: 0.88rem;
        opacity: 0.9;
        margin: 0;
        line-height: 1.6;
    }

    /* View All Button */
    .view-all-container {
        text-align: center;
        margin-top: 40px;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: #6ba932;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-view-all:hover {
        background: #5a9028;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Animations */
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            padding: 50px 0;
        }

        .hero-text h1 {
            font-size: 2rem;
        }

        .hero-text p {
            font-size: 1rem;
        }

        .categories-section,
        .featured-section,
        .features-section {
            padding: 35px 0;
        }

        .section-header h2 {
            font-size: 1.6rem;
        }

        .categories-grid,
        .products-grid {
            gap: 16px;
        }

        .features-grid {
            gap: 20px;
        }
    }

    @media (max-width: 576px) {
        .hero-section {
            padding: 40px 0;
        }

        .hero-text h1 {
            font-size: 1.75rem;
        }

        .hero-buttons {
            flex-direction: column;
        }

        .btn-hero {
            width: 100%;
            justify-content: center;
        }

        .section-header h2 {
            font-size: 1.5rem;
        }

        .category-icon {
            width: 60px;
            height: 60px;
        }

        .category-icon i {
            font-size: 28px;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Your Premier Sports Equipment Destination</h1>
            <p>Discover quality sports gear for every athlete. From beginners to champions, we've got everything you need to play your best game.</p>
            <div class="hero-buttons">
                <a href="{{ route('products.index') }}" class="btn-hero btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    Shop Now
                </a>
                <a href="#features" class="btn-hero btn-secondary">
                    <i class="fas fa-info-circle"></i>
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <p>Find the perfect equipment for your favorite sport</p>
        </div>
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card">
                <div class="category-icon">
                    @if($category->slug == 'badminton')
                        <i class="fas fa-table-tennis"></i>
                    @elseif($category->slug == 'basketball')
                        <i class="fas fa-basketball-ball"></i>
                    @elseif($category->slug == 'volleyball')
                        <i class="fas fa-volleyball-ball"></i>
                    @elseif($category->slug == 'tennis')
                        <i class="fas fa-baseball-ball"></i>
                    @elseif($category->slug == 'chess')
                        <i class="fas fa-chess"></i>
                    @elseif($category->slug == 'accessories')
                        <i class="fas fa-tshirt"></i>
                    @elseif($category->slug == 'pickleball')
                        <i class="fas fa-table-tennis-paddle-ball"></i>
                    @else
                        <i class="fas fa-dumbbell"></i>
                    @endif
                </div>
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->description ?? 'Quality equipment & accessories' }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Check out our best-selling sports equipment</p>
        </div>
        <div class="products-grid">
            @forelse($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    @if($product->isOnSale())
                        <span class="product-badge sale">SALE</span>
                    @endif

                    <!-- Stock Badge -->
                    <span class="stock-badge {{ $product->isInStock() ? ($product->isLowStock() ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                        {{ $product->isInStock() ? ($product->isLowStock() ? 'Low Stock' : 'In Stock') : 'Out of Stock' }}
                    </span>

                    <!-- Fixed Image Display -->
                    <img src="{{ $product->getDisplayImageUrl() }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                </div>
                <div class="product-info">
                    <div class="product-category">{{ $product->category->name }}</div>
                    <div class="product-name">{{ $product->name }}</div>
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
                <h3 style="color: #666; margin-bottom: 8px;">No featured products available</h3>
                <p style="color: #999;">Check back soon for new arrivals</p>
            </div>
            @endforelse
        </div>
        <div class="view-all-container">
            <a href="{{ route('products.index') }}" class="btn-view-all">
                <i class="fas fa-store"></i>
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>Quick and reliable shipping to your location</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Authentic Products</h3>
                <p>100% genuine sports equipment guaranteed</p>
            </div>

            

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Best Prices</h3>
                <p>Quality products at affordable prices</p>
            </div>
        </div>
    </div>
</section>

<script>
// Add animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.category-card, .product-card, .feature-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection
