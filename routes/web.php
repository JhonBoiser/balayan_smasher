<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SearchController;
use Illuminate\Support\Facades\Auth;

// PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// AUTHENTICATION ROUTES - FIXED
Auth::routes(['verify' => false]); // Disable verification if not needed

// MANUAL LOGOUT ROUTE - ADD THIS
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// CUSTOMER ROUTES (AUTH REQUIRED)
Route::middleware(['auth'])->group(function () {
        // Payment Routes
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
            Route::post('/create-intent', [PaymentController::class, 'createIntent'])->name('create-intent');
            Route::post('/process', [PaymentController::class, 'process'])->name('process');
            Route::get('/{orderId}/return', [PaymentController::class, 'handleReturn'])->name('return');
            Route::get('/{orderId}/failure', [PaymentController::class, 'handleFailure'])->name('failure');
        });

    // Cart Routes - UPDATED WITH API ROUTES
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');

        // NEW CART API ROUTES
        Route::get('/summary', [CartController::class, 'getCartSummary'])->name('summary');
        Route::get('/items', [CartController::class, 'getCartItems'])->name('items');
    });

    // Checkout Routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    });

    // Order Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');

        // ORDER CANCELLATION ROUTES - ADDED
        Route::get('/{id}/cancel', [OrderController::class, 'showCancelForm'])->name('cancel.form');
        Route::post('/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');

        // Customer actions on their orders
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{id}/payment', [OrderController::class, 'updatePaymentStatus'])->name('payment');
        Route::post('/{id}/send-email', [OrderController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/send-sms', [OrderController::class, 'sendSms'])->name('send-sms');
    });
});

// ADMIN ROUTES (AUTH + ADMIN MIDDLEWARE)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DASHBOARD API ROUTES FOR REAL-TIME DATA - UPDATED
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Main revenue data for line charts
        Route::get('/revenue-data', [DashboardController::class, 'revenueData'])->name('revenue-data');

        // Real-time stats for live updates
        Route::get('/real-time-stats', [DashboardController::class, 'realTimeStats'])->name('real-time-stats');

        // Order status distribution for bar chart
        Route::get('/order-status-data', [DashboardController::class, 'orderStatusData'])->name('order-status-data');

        // Real-time sales data (hourly)
        Route::get('/real-time-sales', [DashboardController::class, 'realTimeSalesData'])->name('real-time-sales');

        // Sales comparison data
        Route::get('/sales-comparison', [DashboardController::class, 'salesComparison'])->name('sales-comparison');
    });

    // Admin Search Routes
    Route::get('/search', [SearchController::class, 'globalSearch'])->name('search');
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch'])->name('search.advanced');

    // Recent orders for notifications
    Route::get('/orders/recent', [SearchController::class, 'recentOrders'])->name('orders.recent');

    // Admin Products
    Route::resource('products', AdminProductController::class);
    Route::delete('products/image/{id}', [AdminProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::delete('products/{product}/images/{id}', [AdminProductController::class, 'deleteImageFromProduct'])->name('products.images.delete');
    Route::post('products/{product}/images/{id}/set-primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.set-primary');

    // Admin Categories
    Route::resource('categories', AdminCategoryController::class);

    // Admin Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{id}/check-updates', [AdminOrderController::class, 'checkUpdates'])->name('check-updates');

        // ADMIN ORDER CANCELLATION ROUTES - ADDED
        Route::get('/{id}/cancel', [AdminOrderController::class, 'showAdminCancelForm'])->name('cancel.form');
        Route::post('/{id}/cancel', [AdminOrderController::class, 'adminCancelOrder'])->name('cancel');

        Route::get('/export', [AdminOrderController::class, 'export'])->name('export');
        Route::get('/bulk-update', function() {
            $orders = \App\Models\Order::with('user')->latest()->paginate(50);
            return view('admin.orders.bulk-update', compact('orders'));
        })->name('bulk-update');
        Route::post('/bulk-update-status', [AdminOrderController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('payment');
        Route::post('/{id}/send-email', [AdminOrderController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/send-sms', [AdminOrderController::class, 'sendSms'])->name('send-sms');
    });
});

// API ROUTES FOR FRONTEND FUNCTIONALITY
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Search API
    Route::get('/search/products', function (Request $request) {
        $query = $request->get('q');

        $products = \App\Models\Product::with('category')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->take(10)
            ->get(['id', 'name', 'slug', 'price', 'primary_image'])
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'primary_image' => $product->getDisplayImageUrl(),
                    'category_name' => $product->category->name
                ];
            });

        return response()->json(['products' => $products]);
    })->name('search.products');

    // Cart API Routes (additional endpoints)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/count', [CartController::class, 'getCartSummary'])->name('count');
        Route::get('/mini', [CartController::class, 'getCartItems'])->name('mini');
    });
});

// Public legal pages
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');

// Webhook route (no auth required)
Route::post('/webhook/paymongo', [PaymentController::class, 'webhook'])->name('webhook.paymongo');

// FALLBACK ROUTE
Route::fallback(function () {
    return redirect()->route('login');
});
