<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Balayan Smashers Hub</title>
  <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Fixed Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-green: #00b14f;
            --secondary-green: #009241;
            --light-green: #e8f5e9;
            --dark-green: #00732f;
            --accent-orange: #ff6b00;
            --light-bg: #f5f7fa;
            --dark-text: #1a1a1a;
            --gray-text: #666666;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-green);
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: var(--gray-text);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background-color: var(--light-green);
            color: var(--primary-green);
            border-left: 3px solid var(--primary-green);
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .menu-label {
            font-size: 0.75rem;
            color: #999;
            padding: 15px 20px 5px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar {
            width: 400px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            background-color: var(--light-bg);
            transition: all 0.3s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 2px rgba(0, 177, 79, 0.1);
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-text);
        }

        /* Search Results Dropdown */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            margin-top: 5px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
        }

        .search-results.active {
            display: block;
        }

        .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }

        .search-result-item:hover {
            background-color: var(--light-green);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .result-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 0.9rem;
            color: white;
        }

        .result-info {
            flex: 1;
        }

        .result-title {
            font-weight: 500;
            margin-bottom: 2px;
        }

        .result-meta {
            font-size: 0.8rem;
            color: var(--gray-text);
        }

        .result-type {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            background: var(--light-bg);
            color: var(--gray-text);
        }

        .search-section {
            padding: 10px 15px;
            background: var(--light-bg);
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--gray-text);
            border-bottom: 1px solid var(--border-color);
        }

        .no-results {
            padding: 20px;
            text-align: center;
            color: var(--gray-text);
        }

        .view-all-results {
            padding: 12px 15px;
            text-align: center;
            background: var(--light-bg);
            color: var(--primary-green);
            font-weight: 500;
            cursor: pointer;
            border-top: 1px solid var(--border-color);
        }

        .view-all-results:hover {
            background: var(--light-green);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-green);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        /* Notification Styles */
        .notification-container {
            position: relative;
            margin-right: 20px;
        }

        .notification-badge {
            font-size: 1.2rem;
            color: var(--gray-text);
            cursor: pointer;
            position: relative;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .notification-badge:hover {
            background-color: var(--light-green);
            color: var(--primary-green);
        }

        .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-orange);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        .badge-count.new {
            animation: bounce 0.5s ease-in-out;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1060;
            display: none;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .notification-clear {
            background: none;
            border: none;
            color: var(--primary-green);
            font-size: 0.8rem;
            cursor: pointer;
        }

        .notification-clear:hover {
            text-decoration: underline;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: var(--light-green);
        }

        .notification-item.unread {
            background-color: #f0f9ff;
            border-left: 3px solid var(--primary-green);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: white;
            flex-shrink: 0;
        }

        .notification-details {
            flex: 1;
        }

        .notification-message {
            font-weight: 500;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.75rem;
            color: var(--gray-text);
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }

        .notification-action {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 12px;
            background: var(--light-bg);
            color: var(--gray-text);
            cursor: pointer;
        }

        .notification-action.view {
            background: var(--primary-green);
            color: white;
        }

        .notification-footer {
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }

        .notification-view-all {
            color: var(--primary-green);
            font-weight: 500;
            text-decoration: none;
        }

        .notification-view-all:hover {
            text-decoration: underline;
        }

        .no-notifications {
            padding: 30px 20px;
            text-align: center;
            color: var(--gray-text);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--gray-text);
            margin: 0;
        }

        .stat-trend {
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .trend-up {
            color: var(--primary-green);
        }

        .trend-down {
            color: #ff3b30;
        }

        /* Animations */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        @keyframes bounce {

            0%,
            20%,
            60%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-5px);
            }

            80% {
                transform: translateY(-2px);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-item.new {
            animation: slideIn 0.3s ease-out;
        }

        /* Charts Section */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Tables */
        .table-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .table-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .btn-primary-custom {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-green);
            border-color: var(--secondary-green);
            color: white;
            text-decoration: none;
        }

        .btn-outline-custom {
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            background: white;
            cursor: pointer;
        }

        .btn-outline-custom:hover {
            background-color: var(--light-green);
            color: var(--primary-green);
            text-decoration: none;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom th {
            background-color: var(--light-bg);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-text);
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d1edff;
            color: #0c5460;
        }

        .status-processing {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Toggle Sidebar Button */
        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-text);
        }

        /* Alert Styles */
        .alert-custom {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }

            .brand-name,
            .menu-item span,
            .menu-label {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 15px 0;
            }

            .menu-item i {
                margin-right: 0;
                font-size: 1.2rem;
            }

            .main-content {
                margin-left: 70px;
            }

            .search-bar {
                width: 300px;
            }

            .notification-dropdown {
                width: 300px;
                right: -50px;
            }

            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }

            .search-bar {
                width: 200px;
            }

            .notification-dropdown {
                position: fixed;
                left: 20px;
                right: 20px;
                top: 120px;
                width: auto;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .search-results {
                position: fixed;
                left: 20px;
                right: 20px;
                top: 120px;
            }
        }

        @media (max-width: 576px) {
            .top-nav {
                flex-direction: column;
                gap: 15px;
            }

            .search-bar {
                width: 100%;
            }

            .user-info {
                width: 100%;
                justify-content: space-between;
            }

            .notification-dropdown {
                left: 10px;
                right: 10px;
            }
        }

        .search-result-item.active {
    background-color: var(--light-green) !important;
    border-left: 3px solid var(--primary-green) !important;
}
    </style>

    @yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <span class="brand-name"><i class="fas fa-store"></i> Admin</span>
            </div>
        </div>

        <div class="sidebar-menu">
            <div class="menu-label">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.orders.index') }}"
                class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>

            <div class="menu-label">Store</div>
            <a href="{{ route('home') }}" class="menu-item">
                <i class="fas fa-store"></i>
                <span>View Store</span>
            </a>

            <div class="menu-label">Account</div>
            <a href="{{ route('logout') }}" class="menu-item"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <button class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="globalSearch" placeholder="Search order #">
                <div class="search-results" id="searchResults">
                    <!-- Search results will be populated here -->
                </div>
            </div>

            <div class="user-info">


                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role text-muted">Administrator</div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>
       <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Toggle sidebar on mobile
    document.querySelector('.toggle-sidebar')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Notification system
    class NotificationSystem {
        constructor() {
            this.notifications = JSON.parse(localStorage.getItem('adminNotifications')) || [];
            this.unreadCount = this.notifications.filter(n => !n.read).length;
            this.updateNotificationUI();
            this.startPolling();
        }

        // Poll for new orders every 30 seconds
        startPolling() {
            setInterval(() => this.checkNewOrders(), 30000);
            // Initial check
            this.checkNewOrders();
        }

        // Check for new orders via API
        async checkNewOrders() {
            try {
                const response = await fetch('/admin/orders/recent', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const orders = await response.json();
                    this.processNewOrders(orders);
                }
            } catch (error) {
                console.error('Failed to fetch recent orders:', error);
            }
        }

        // Process new orders and create notifications
        processNewOrders(orders) {
            let newNotifications = false;

            orders.forEach(order => {
                // Check if we already have a notification for this order
                const existingNotification = this.notifications.find(n => n.id === `order_${order.id}`);

                if (!existingNotification) {
                    const notification = {
                        id: `order_${order.id}`,
                        type: 'new_order',
                        title: 'New Order Received',
                        message: `Order #${order.order_number} from ${order.customer_name}`,
                        amount: order.total ? `₱${parseFloat(order.total).toLocaleString()}` : '₱0.00',
                        time: new Date().toISOString(),
                        read: false,
                        orderId: order.id,
                        url: `/admin/orders/${order.id}`
                    };

                    this.notifications.unshift(notification);
                    newNotifications = true;

                    // Show desktop notification if supported
                    this.showDesktopNotification(notification);
                }
            });

            if (newNotifications) {
                this.saveNotifications();
                this.updateNotificationUI();
                this.animateNewNotification();
            }
        }

        // Show desktop notification
        showDesktopNotification(notification) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('New Order - Balayan Smashers Hub', {
                    body: notification.message,
                    icon: '/favicon.ico',
                    tag: notification.id
                });
            }
        }

        // Request notification permission
        requestNotificationPermission() {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        }

        // Update notification UI
        updateNotificationUI() {
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');

            this.unreadCount = this.notifications.filter(n => !n.read).length;

            // Update badge count
            if (notificationCount) {
                notificationCount.textContent = this.unreadCount;
                if (this.unreadCount > 0) {
                    notificationCount.classList.add('new');
                } else {
                    notificationCount.classList.remove('new');
                }
            }

            // Update notification list
            if (notificationList) {
                if (this.notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                            <p>No notifications</p>
                            <small class="text-muted">New orders will appear here</small>
                        </div>
                    `;
                } else {
                    let html = '';
                    this.notifications.forEach(notification => {
                        const timeAgo = this.getTimeAgo(notification.time);
                        const isUnread = !notification.read ? 'unread' : '';

                        html += `
                            <div class="notification-item ${isUnread}" data-id="${notification.id}">
                                <div class="notification-content">
                                    <div class="notification-icon" style="background: #00b14f;">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="notification-details">
                                        <div class="notification-message">${notification.message}</div>
                                        <div class="notification-time">${timeAgo} • ${notification.amount}</div>
                                        <div class="notification-actions">
                                            <span class="notification-action view" onclick="notificationSystem.viewOrder('${notification.orderId}')">View Order</span>
                                            <span class="notification-action" onclick="notificationSystem.markAsRead('${notification.id}')">Mark Read</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    notificationList.innerHTML = html;
                }
            }
        }

        // Animate new notification
        animateNewNotification() {
            const badge = document.getElementById('notificationCount');
            if (badge) {
                badge.classList.add('new');
                setTimeout(() => badge.classList.remove('new'), 2000);
            }
        }

        // Mark notification as read
        markAsRead(notificationId) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.read = true;
                this.saveNotifications();
                this.updateNotificationUI();
            }
        }

        // Mark all as read
        markAllAsRead() {
            this.notifications.forEach(notification => {
                notification.read = true;
            });
            this.saveNotifications();
            this.updateNotificationUI();
        }

        // Clear all notifications
        clearAll() {
            this.notifications = [];
            this.saveNotifications();
            this.updateNotificationUI();
        }

        // View order
        viewOrder(orderId) {
            window.location.href = `/admin/orders/${orderId}`;
        }

        // Get time ago string
        getTimeAgo(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diffInSeconds = Math.floor((now - time) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            return `${Math.floor(diffInSeconds / 86400)}d ago`;
        }

        // Save notifications to localStorage
        saveNotifications() {
            // Keep only last 50 notifications
            if (this.notifications.length > 50) {
                this.notifications = this.notifications.slice(0, 50);
            }
            localStorage.setItem('adminNotifications', JSON.stringify(this.notifications));
        }
    }

    // Initialize notification system
    const notificationSystem = new NotificationSystem();

    // Notification dropdown toggle
    document.getElementById('notificationBtn')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('active');

        // Mark all as read when opening dropdown
        if (dropdown.classList.contains('active')) {
            notificationSystem.markAllAsRead();
        }
    });

    // Clear all notifications
    document.getElementById('clearNotifications')?.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationSystem.clearAll();
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.remove('active');
        }
    });

    // Prevent dropdown close when clicking inside
    document.getElementById('notificationDropdown')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Request notification permission on page load
    document.addEventListener('DOMContentLoaded', function() {
        notificationSystem.requestNotificationPermission();
    });

    // Global search functionality
    let searchTimeout;
    const searchInput = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');

    // CSRF token for API requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Real-time search function
    async function performSearch(searchTerm) {
        if (searchTerm.length < 2) {
            searchResults.classList.remove('active');
            return;
        }

        try {
            // Show loading state
            searchResults.innerHTML = `
                <div class="no-results">
                    <div class="loading-spinner" style="width: 20px; height: 20px; border: 2px solid #f3f3f3; border-top: 2px solid #00b14f; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
                    <p>Searching...</p>
                </div>
            `;
            searchResults.classList.add('active');

            // Make API request to search endpoint
            const response = await fetch(`/admin/search?q=${encodeURIComponent(searchTerm)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`Search failed: ${response.status}`);
            }

            const data = await response.json();
            displaySearchResults(data, searchTerm);

        } catch (error) {
            console.error('Search error:', error);
            // Show error message
            searchResults.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                    <p>Not Found</p>
                    <small class="text-muted">Try again</small>
                </div>
            `;
            searchResults.classList.add('active');
        }
    }

    // Display search results from API
    function displaySearchResults(data, searchTerm) {
        const results = data.results || data;

        if (!results || Object.keys(results).length === 0) {
            searchResults.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p>No results found for "${escapeHtml(searchTerm)}"</p>
                    <small class="text-muted">Try searching with different keywords</small>
                </div>
            `;
            return;
        }

        let html = '';
        let totalResults = 0;

        // Products section
        if (results.products && results.products.length > 0) {
            html += `<div class="search-section">Products (${results.products.length})</div>`;
            results.products.forEach(product => {
                html += createSearchResultItem({
                    icon: 'shopping-bag',
                    iconColor: '#00b14f',
                    title: product.name,
                    meta: `${product.sku || 'N/A'} • ${formatPrice(product.price)} • ${product.category || 'Uncategorized'}`,
                    type: 'Product',
                    url: product.url || `/admin/products/${product.id}/edit`
                });
            });
            totalResults += results.products.length;
        }

        // Orders section
        if (results.orders && results.orders.length > 0) {
            html += `<div class="search-section">Orders (${results.orders.length})</div>`;
            results.orders.forEach(order => {
                const statusClass = getStatusClass(order.status);
                html += createSearchResultItem({
                    icon: 'shopping-cart',
                    iconColor: '#ff6b00',
                    title: `Order #${order.order_number || order.id}`,
                    meta: `${order.customer_name || 'Unknown Customer'} • ${formatPrice(order.total)} • <span class="status-badge ${statusClass}">${order.status}</span>`,
                    type: 'Order',
                    url: order.url || `/admin/orders/${order.id}`
                });
            });
            totalResults += results.orders.length;
        }

        // Customers section
        if (results.customers && results.customers.length > 0) {
            html += `<div class="search-section">Customers (${results.customers.length})</div>`;
            results.customers.forEach(customer => {
                html += createSearchResultItem({
                    icon: 'user',
                    iconColor: '#3498db',
                    title: customer.name,
                    meta: `${customer.email} • ${customer.orders_count || 0} orders`,
                    type: 'Customer',
                    url: customer.url || `/admin/customers/${customer.id}`
                });
            });
            totalResults += results.customers.length;
        }

        // Categories section
        if (results.categories && results.categories.length > 0) {
            html += `<div class="search-section">Categories (${results.categories.length})</div>`;
            results.categories.forEach(category => {
                html += createSearchResultItem({
                    icon: 'tags',
                    iconColor: '#9b59b6',
                    title: category.name,
                    meta: `${category.products_count || 0} products`,
                    type: 'Category',
                    url: category.url || `/admin/categories/${category.id}/edit`
                });
            });
            totalResults += results.categories.length;
        }

        // View all results link
        if (totalResults > 0) {
            html += `
                <div class="view-all-results" onclick="viewAllResults('${escapeHtml(searchTerm)}')">
                    View all ${totalResults} results for "${escapeHtml(searchTerm)}"
                </div>
            `;
        }

        searchResults.innerHTML = html;
        searchResults.classList.add('active');
    }

    // Create search result item HTML
    function createSearchResultItem({ icon, iconColor, title, meta, type, url }) {
        return `
            <div class="search-result-item" onclick="navigateTo('${url}')">
                <div class="result-icon" style="background: ${iconColor};">
                    <i class="fas fa-${icon}"></i>
                </div>
                <div class="result-info">
                    <div class="result-title">${escapeHtml(title)}</div>
                    <div class="result-meta">${meta}</div>
                </div>
                <div class="result-type">${type}</div>
            </div>
        `;
    }

    // Helper functions
    function getStatusClass(status) {
        const statusClasses = {
            'pending': 'status-pending',
            'processing': 'status-processing',
            'shipped': 'status-completed',
            'delivered': 'status-completed',
            'completed': 'status-completed',
            'cancelled': 'status-cancelled',
            'refunded': 'status-cancelled'
        };
        return statusClasses[status?.toLowerCase()] || 'status-pending';
    }

    function formatPrice(price) {
        if (!price) return '₱0.00';
        return `₱${parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
    }

    function navigateTo(url) {
        if (url && url !== '#') {
            window.location.href = url;
        }
        searchResults.classList.remove('active');
        searchInput.value = '';
    }

    function viewAllResults(searchTerm) {
        window.location.href = `/admin/search?q=${encodeURIComponent(searchTerm)}`;
    }

    // Event listeners for real-time search
    searchInput?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.trim();

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Hide results if empty
        if (searchTerm.length === 0) {
            searchResults.classList.remove('active');
            return;
        }

        // Set new timeout for search (debounce)
        searchTimeout = setTimeout(() => {
            performSearch(searchTerm);
        }, 300);
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-bar')) {
            searchResults.classList.remove('active');
        }
    });

    // Handle keyboard navigation for search
    searchInput?.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchResults.classList.remove('active');
            searchInput.blur();
        }

        if (e.key === 'Enter') {
            const firstResult = searchResults.querySelector('.search-result-item');
            if (firstResult) {
                firstResult.click();
            } else if (searchInput.value.trim().length > 0) {
                // If no results but search term exists, navigate to search page
                viewAllResults(searchInput.value.trim());
            }
        }

        // Arrow key navigation through results
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            const results = searchResults.querySelectorAll('.search-result-item');
            if (results.length === 0) return;

            const currentActive = searchResults.querySelector('.search-result-item.active');
            let nextActive;

            if (!currentActive) {
                nextActive = e.key === 'ArrowDown' ? results[0] : results[results.length - 1];
            } else {
                const currentIndex = Array.from(results).indexOf(currentActive);
                if (e.key === 'ArrowDown') {
                    nextActive = results[(currentIndex + 1) % results.length];
                } else {
                    nextActive = results[(currentIndex - 1 + results.length) % results.length];
                }
            }

            // Update active state
            results.forEach(r => r.classList.remove('active'));
            nextActive.classList.add('active');
            nextActive.scrollIntoView({ block: 'nearest' });
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

        // Add CSS for loading spinner and active search items
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .search-result-item.active {
                background-color: var(--light-green) !important;
                border-left: 3px solid var(--primary-green) !important;
            }
        `;
        document.head.appendChild(style);
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-sidebar');

        if (window.innerWidth <= 768 &&
            sidebar &&
            sidebar.classList.contains('active') &&
            !sidebar.contains(event.target) &&
            toggleBtn &&
            !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Prevent form submission when pressing enter in search
    searchInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Global dialog/modal helper function
    function showDialog(title, message, type = 'info') {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'globalDialog';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-labelledby', 'globalDialogLabel');
        modal.setAttribute('aria-hidden', 'true');

        // Determine icon and color based on type
        let iconClass = 'fa-info-circle';
        let bgColor = '#17a2b8'; // info

        if (type === 'success') {
            iconClass = 'fa-check-circle';
            bgColor = '#28a745';
        } else if (type === 'error') {
            iconClass = 'fa-exclamation-circle';
            bgColor = '#dc3545';
        } else if (type === 'warning') {
            iconClass = 'fa-exclamation-triangle';
            bgColor = '#ffc107';
        }

        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: ${bgColor}; color: white; border: none;">
                        <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
                            <i class="fas ${iconClass}" style="font-size: 1.5rem;"></i>
                            <h5 class="modal-title" id="globalDialogLabel" style="margin: 0;">${escapeHtml(title)}</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p style="margin: 0; color: #333; line-height: 1.6;">${escapeHtml(message)}</p>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e0e0e0;">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `;

        // Remove any existing dialog
        const existingDialog = document.getElementById('globalDialog');
        if (existingDialog) {
            existingDialog.remove();
        }

        // Add to body and show
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Clean up after modal is hidden
        modal.addEventListener('hidden.bs.modal', function() {
            modal.remove();
        });
    }
</script>

    @yield('scripts')
</body>

</html>
