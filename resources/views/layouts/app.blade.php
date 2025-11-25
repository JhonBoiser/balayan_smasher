{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Balayan Smashers Hub - Your One-Stop Sports Shop')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #002608;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --btn-primary: rgb(4, 148, 4);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #1f63c9;
        }

        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }

        .badge-sale {
            background: var(--primary-color);
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        .footer {
            background: var(--secondary-color);
            color: white;
            padding: 40px 0 20px;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
        }


    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">

            <a class="navbar-brand" href="{{ route('home') }}">
                <img style="width:50px; height: 50px; border-radius: 50%;" src="https://scontent.fmnl17-2.fna.fbcdn.net/v/t39.30808-1/548792979_122150760740625486_4368863154810254932_n.jpg?stp=cp0_dst-jpg_s60x60_tt6&_nc_cat=111&ccb=1-7&_nc_sid=2d3e12&_nc_eui2=AeEPnAUL10yGlAlX7Vnjg1mJIlJWwLuyX0ciUlbAu7JfRzm5RWPIwmy2Fyg7ezO7G1dPGBzYud5IVYN1oY6Sb0zf&_nc_ohc=shK1zjvBj48Q7kNvwGBYoOe&_nc_oc=AdlahgD9tUOjoFQVSSJN-6Y4rfs-WA_dme9hHefJ5zRllp9oM9KmVOpTG4C4cV78ihs&_nc_zt=24&_nc_ht=scontent.fmnl17-2.fna&_nc_gid=2M6G0c5I0x3U9k_DWGQzPA&oh=00_AfiN_AC7RNmL5_BjqXLJDnDN9tZvt3b_F51rPs0FBJRVqQ&oe=6913751E" alt=""> Balayan Smashers Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">My Orders</a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i> Cart
                                @if(auth()->check() && auth()->user()->cart->count() > 0)
                                    <span class="cart-badge">{{ auth()->user()->cart->count() }}</span>
                                @endif
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-shop"></i> Balayan Smashers Hub</h5>
                    <p>Your one-stop shop for all badminton gear and sporting equipment in Balayan, Batangas.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-envelope fs-4"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white-50 text-decoration-none">Cart</a></li>
                        @auth
                            <li><a href="{{ route('orders.index') }}" class="text-white-50 text-decoration-none">My Orders</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <p class="mb-1"><i class="bi bi-geo-alt-fill"></i> Calzada, Ermita, Balayan, Batangas</p>
                    <p class="mb-1"><i class="bi bi-telephone-fill"></i> +63 906 623 8257</p>
                    <p><i class="bi bi-envelope-fill"></i> info@balayan-smashers.com</p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Balayan Smashers Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
