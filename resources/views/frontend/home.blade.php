{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Balayan Smashers Hub - Quality Sports Equipment')

@section('content')
<!-- Hero Carousel Section -->
<section class="hero-carousel">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://scontent.fmnl17-6.fna.fbcdn.net/v/t39.30808-6/528606912_122164303472392515_5841549823835529491_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFFAWIZnSqRpTbPixURpsqDSns-POsxGJxKez486zEYnGvMfF8VCSg8kEGVEN1howQ6hdsh2RDECho6-0LTrY5S&_nc_ohc=kEZ8PK5kXR0Q7kNvwGvJoCp&_nc_oc=AdkO9pwB6-HAL0WkT2pZ6hHbMykdNznF1O0JllPKtmGh-U9IZRfP104zmsDSnjPmvKY&_nc_zt=23&_nc_ht=scontent.fmnl17-6.fna&_nc_gid=tAGMvbHw6eGhgTk2XhX7dA&oh=00_Afg3uex0vo9tM__pbb3K2AG34NafJp3LLF4_-mEsPfKqPg&oe=691349CA"
                     class="d-block w-100" alt="Badminton Equipment">
                <div class="carousel-caption text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        Welcome to <span style="color: var(--btn-primary);">Balayan Smashers Hub</span>
                    </h1>
                    <p class="lead mb-4">
                        Your one-stop shop for quality badminton gear and sporting equipment in Balayan, Batangas.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop"></i> Shop Now
                    </a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://scontent.fmnl17-1.fna.fbcdn.net/v/t39.30808-6/528596440_122164303460392515_8277941324246145087_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=101&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFXqBwj0tY1RXQWW7WJa430jOpjAIOSxp6M6mMAg5LGnkAUlR66XvWdLaNf5-6VUDdMHKepPaS2HsOjHcreBxCs&_nc_ohc=U8g_hNWasl8Q7kNvwH1yxIN&_nc_oc=AdkeQKibB83whLvrYUjzpAH1Mm9LVMVazFtS5l-mh0xWJX_di0x1ouFWV9HLwU40zDM&_nc_zt=23&_nc_ht=scontent.fmnl17-1.fna&_nc_gid=EeIMN1LmQ5U3v5l0Xf4tmw&oh=00_AfjfPV8Oyjfe8F3e0K0lmpkHAKv_wvQ33xRglL94IlD3wQ&oe=69137297"
                     class="d-block w-100" alt="Basketball Equipment">
                <div class="carousel-caption text-center">
                    <h1 class="display-4 fw-bold mb-3">Premium Sports Equipment</h1>
                    <p class="lead mb-4">
                        Discover our wide range of basketball, volleyball, and other sports gear.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop"></i> Shop Now
                    </a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://scontent.fmnl17-4.fna.fbcdn.net/v/t39.30808-6/528829526_122164303448392515_598032655323847237_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=105&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFxgeLNN802r72YRBOdPxpGa-Sis7R9C1Zr5KKztH0LVpg52T63UNUKpNMEQrckENspU_i1i2PeHCPvEpP2BoNr&_nc_ohc=V7ZgYCIITSwQ7kNvwEag-Rb&_nc_oc=AdlinndSQUd2iFwWryREjhFqqbcjnRONgZcoxrCPfKaaSFtzjLO0Vc9MzAUO8ChKIwQ&_nc_zt=23&_nc_ht=scontent.fmnl17-4.fna&_nc_gid=UopGT7eSO_bN8xsiC-LrIg&oh=00_AfiN0RcUPexrgch6xjTDcSS4Ksdu9iu-WXrBUQYsgAYLHQ&oe=69135B72"
                     class="d-block w-100" alt="Volleyball Equipment">
                <div class="carousel-caption text-center">
                    <h1 class="display-4 fw-bold mb-3">Quality & Performance</h1>
                    <p class="lead mb-4">
                        Elevate your game with our high-performance sporting goods and accessories.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop"></i> Shop Now
                    </a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Shop by Category</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card h-100 text-center product-card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                @if($category->slug == 'badminton')
                                    <i class="bi bi-person-arms-up" style="font-size: 3rem; color: var(--primary-color);"></i>
                                @elseif($category->slug == 'basketball')
                                    <i class="bi bi-circle" style="font-size: 3rem; color: var(--accent-color);"></i>
                                @elseif($category->slug == 'volleyball')
                                    <i class="bi bi-circle-fill" style="font-size: 3rem; color: var(--secondary-color);"></i>
                                @else
                                    <i class="bi bi-bag-fill" style="font-size: 3rem; color: var(--primary-color);"></i>
                                @endif
                            </div>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted small">{{ $category->description }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 product-card shadow-sm position-relative">
                    @if($product->isOnSale())
                        <span class="badge badge-sale">SALE</span>
                    @endif

                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                             class="card-img-top product-image"
                             alt="{{ $product->name }}">
                    @else
                        <img src="https://tse2.mm.bing.net/th/id/OIP.Em_MJNuvUgNU33oSE66ReQHaHa?pid=Api&P=0&h=180{{ urlencode($product->name) }}"
                             class="card-img-top product-image"
                             alt="{{ $product->name }}">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 align-self-start">{{ $product->category->name }}</span>
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($product->description, 60) }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($product->isOnSale())
                                        <span class="text-danger fw-bold">₱{{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-muted text-decoration-line-through d-block">
                                            ₱{{ number_format($product->price, 2) }}
                                        </small>
                                    @else
                                        <span class="fw-bold">₱{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="badge {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->isInStock() ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No featured products available.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Why Choose Us?</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-award-fill" style="font-size: 3rem; color: var(--primary-color);"></i>
                </div>
                <h5>Quality Products</h5>
                <p class="text-muted">We only sell authentic and high-quality sports equipment from trusted brands.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-truck" style="font-size: 3rem; color: var(--accent-color);"></i>
                </div>
                <h5>Fast Delivery</h5>
                <p class="text-muted">Quick and reliable delivery service throughout Balayan and nearby areas.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-headset" style="font-size: 3rem; color: var(--secondary-color);"></i>
                </div>
                <h5>Expert Support</h5>
                <p class="text-muted">Our team is always ready to help you find the perfect equipment for your needs.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .hero-carousel {
        height: 70vh;
        min-height: 500px;
        overflow: hidden;
        margin-top: -1rem; /* Adjust if needed based on your navbar height */
    }

    .carousel-item {
        height: 70vh;
        min-height: 500px;
    }

    .carousel-item img {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
        padding: 2rem;
        bottom: 20%;
        left: 10%;
        right: 10%;
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }

    .badge-sale {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #1400aa;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 1;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 5%;
    }

    @media (max-width: 768px) {
        .hero-carousel, .carousel-item {
            height: 50vh;
            min-height: 400px;
        }

        .carousel-caption {
            bottom: 10%;
            padding: 1rem;
        }

        .carousel-caption h1 {
            font-size: 1.8rem;
        }

        .carousel-caption .lead {
            font-size: 1rem;
        }
    }
</style>
@endsection
