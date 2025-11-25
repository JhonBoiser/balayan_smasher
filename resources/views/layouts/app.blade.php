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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <style>
        :root {
            --primary-green: #6ba932;
            --secondary-green: #5a9028;
            --light-bg: #f5f7fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1400px;
            margin: 0 auto;
            padding: 10px 24px;
        }

        .logo-container img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
        }

        .logo-text {
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin-left: 10px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 8px;
            margin: 0;
            padding: 0;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.15);
        }

        .header-actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 600;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            text-decoration: none;
        }

        .cart-badge {
            background: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 10px;
            font-weight: 700;
        }

        .user-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 8px 0;
            z-index: 1001;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            color: var(--primary-green);
        }

        .mobile-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Search Overlay */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .search-overlay.active {
            display: flex;
        }

        .search-box {
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            font-size: 1.1rem;
            border: none;
            border-radius: 50px;
            outline: none;
        }

        .search-box button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
        }

        .close-search {
            position: absolute;
            top: 24px;
            right: 24px;
            background: transparent;
            border: 2px solid white;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 22px;
        }

        /* Footer Styles - Preserved */
        .main-footer {
            background: linear-gradient(180deg, #1a1a1a 0%, #0d0d0d 100%);
            color: white;
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }

        .main-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #6ba932 0%, #5a9028 50%, #6ba932 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 45px 24px 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* Footer Brand Section */
        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .footer-logo {
            width: 150px;
            margin-bottom: 8px;
            transition: transform 0.3s ease;
        }

        .footer-logo:hover {
            transform: scale(1.05);
        }

        .footer-description {
            color: #b0b0b0;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .footer-contact-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #b0b0b0;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .contact-item i {
            color: #6ba932;
            font-size: 14px;
            width: 18px;
        }

        .contact-item:hover {
            color: #6ba932;
        }

        /* Footer Sections */
        .footer-section {
            display: flex;
            flex-direction: column;
        }

        .footer-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 45px;
            height: 3px;
            background: linear-gradient(90deg, #6ba932 0%, transparent 100%);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-link {
            color: #b0b0b0;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.3s ease;
            position: relative;
            padding-left: 0;
        }

        .footer-link::before {
            content: '›';
            color: #6ba932;
            font-size: 16px;
            font-weight: bold;
            opacity: 0;
            transform: translateX(-8px);
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #6ba932;
            padding-left: 12px;
        }

        .footer-link:hover::before {
            opacity: 1;
            transform: translateX(0);
        }

        /* Social Media Section */
        .social-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .social-icons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .social-icon-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .social-icon-box:hover {
            transform: translateY(-4px);
            border-color: #6ba932;
            box-shadow: 0 8px 20px rgba(107, 169, 50, 0.2);
        }

        .social-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .social-icon-box.facebook .social-icon-circle {
            background: linear-gradient(135deg, #1877f2, #0c5ece);
        }

        .social-icon-box.instagram .social-icon-circle {
            background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .social-icon-box.tiktok .social-icon-circle {
            background: linear-gradient(135deg, #000000, #333333);
        }

        .social-icon-box:hover .social-icon-circle {
            transform: scale(1.1) rotate(5deg);
        }

        .social-icon-circle i {
            font-size: 16px;
            color: white;
        }

        .social-label {
            font-size: 12px;
            font-weight: 600;
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 24px;
            text-align: center;
        }

        .footer-copyright {
            color: #888;
            font-size: 13px;
        }

        .footer-copyright strong {
            color: #6ba932;
            font-weight: 600;
        }

        /* Responsive Design for Footer */
        @media (max-width: 1200px) {
            .footer-grid {
                grid-template-columns: 2fr 1fr 1fr;
                gap: 32px;
            }

            .social-section {
                grid-column: 1 / -1;
            }

            .social-icons-grid {
                grid-template-columns: repeat(3, 1fr);
                max-width: 600px;
            }
        }

        @media (max-width: 768px) {
            .footer-container {
                padding: 32px 16px 20px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
                margin-bottom: 28px;
            }

            .footer-brand {
                text-align: center;
                align-items: center;
            }

            .footer-logo {
                width: 130px;
            }

            .footer-contact-info {
                align-items: center;
            }

            .footer-title {
                text-align: center;
            }

            .footer-title::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links {
                align-items: center;
            }

            .social-icons-grid {
                grid-template-columns: 1fr;
                width: 100%;
            }

            .footer-bottom {
                padding-top: 20px;
            }
        }

        @media (max-width: 576px) {
            .main-footer {
                margin-top: 40px;
            }

            .footer-container {
                padding: 24px 12px 16px;
            }

            .footer-title {
                font-size: 15px;
                margin-bottom: 16px;
            }

            .footer-description,
            .contact-item,
            .footer-link,
            .footer-copyright {
                font-size: 12px;
            }
        }

        /* Header Responsive */
        @media (max-width: 992px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
                flex-direction: column;
                gap: 0;
                padding: 16px 0;
            }

            .nav-menu.active {
                display: flex;
            }

            .mobile-toggle {
                display: block;
            }

            .action-btn span {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 10px 16px;
            }
        }

        @media (max-width: 576px) {
            .header-actions {
                gap: 4px;
            }

            .action-btn {
                padding: 7px 9px;
            }

            .logo-container img {
                height: 38px;
                width: 38px;
            }
        }
    </style>
</head>

<body>
    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay">
        <button class="close-search" onclick="closeSearch()">
            <i class="fas fa-times"></i>
        </button>
        <div class="search-box">
            <input type="text" placeholder="Search for products, brands, and more...">
            <button><i class="fas fa-search"></i></button>
        </div>
    </div>

    <header class="main-header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo-container">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="https://th.bing.com/th/id/OIP.iyU99v5mL6DEKe2bKcn8kAHaHa?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3"
                        alt="Balayan Smashers Hub Logo">
                    <span class="logo-text"></span>
                </a>
            </div>

            {{-- In your app.blade.php file, update the nav-menu section --}}
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    <li><a href="{{ route('products.index') }}"
                            class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a></li>
                    <li><a href="{{ route('about') }}"
                            class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    {{-- Add this line --}}

                    @auth
                        @if (auth()->user()->isCustomer())
                            <li><a href="{{ route('orders.index') }}"
                                    class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">My Orders</a></li>
                        @endif
                    @endauth
                </ul>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <button class="action-btn" onclick="openSearch()">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>

                @auth
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="action-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Cart</span>
                        @if (auth()->check() && auth()->user()->cart && auth()->user()->cart->count() > 0)
                            <span class="cart-badge">{{ auth()->user()->cart->count() }}</span>
                        @endif
                    </a>

                    <!-- Admin Dashboard -->
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="action-btn">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="action-btn" onclick="toggleDropdown()">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdown">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <button class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Guest Actions -->
                    <a href="{{ route('login') }}" class="action-btn">
                        <i class="fas fa-user"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="action-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                @endauth

                <!-- Mobile Toggle -->
                <button class="mobile-toggle" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <h5><i class="fas fa-store"></i> Balayan Smashers Hub</h5>
                    <p class="footer-description">
                        Your one-stop shop for all badminton gear and sporting equipment in Balayan, Batangas.
                    </p>
                    <div class="footer-contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Calzada, Ermita, Balayan, Batangas</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+63 906 623 8257</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@balayan-smashers.com</span>
                        </div>
                    </div>
                </div>
                {{-- In your app.blade.php footer section, update the quick links --}}
                <div class="footer-section">
                    <h3 class="footer-title">Quick Links</h3>
                    <div class="footer-links">
                        <a href="{{ route('home') }}" class="footer-link">Home</a>
                        <a href="{{ route('products.index') }}" class="footer-link">Products</a>
                        <a href="{{ route('about') }}" class="footer-link">About</a>
                        <a href="{{ route('contact') }}" class="footer-link">Contact</a> {{-- Add this line --}}
                        <a href="{{ route('cart.index') }}" class="footer-link">Cart</a>
                        @auth
                            <a href="{{ route('orders.index') }}" class="footer-link">My Orders</a>
                        @endauth
                    </div>
                </div>
                <!-- Support Section -->
                <div class="footer-section">
                    <h3 class="footer-title">Support</h3>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Order Tracking</a>
                        <a href="#" class="footer-link">Payment Options</a>
                        <a href="#" class="footer-link">Return Policy</a>
                        <a href="#" class="footer-link">Terms of Service</a>
                        <a href="#" class="footer-link">Privacy Policy</a>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="footer-section social-section">
                    <h3 class="footer-title">Connect With Us</h3>
                    <div class="social-icons-grid">
                        <a href="https://www.facebook.com/brylle.s.sports.and.equipment.store" target="_blank"
                            class="social-icon-box facebook">
                            <div class="social-icon-circle">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <span class="social-label">Facebook</span>
                        </a>
                        <a href="#" target="_blank" class="social-icon-box instagram">
                            <div class="social-icon-circle">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <span class="social-label">Instagram</span>
                        </a>
                        <a href="#" target="_blank" class="social-icon-box tiktok">
                            <div class="social-icon-circle">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span class="social-label">Email</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} <strong>Balayan Smashers Hub</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle mobile menu
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Toggle user dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Open search overlay
        function openSearch() {
            document.getElementById('searchOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close search overlay
        function closeSearch() {
            document.getElementById('searchOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close search on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearch();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const dropdownBtn = document.querySelector('.user-dropdown .action-btn');

            if (!event.target.closest('.user-dropdown')) {
                dropdown.classList.remove('show');
            }

            // Close mobile menu when clicking outside
            const navMenu = document.getElementById('navMenu');
            const mobileToggle = document.querySelector('.mobile-toggle');

            if (!event.target.closest('.nav-menu') && !event.target.closest('.mobile-toggle')) {
                navMenu.classList.remove('active');
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
