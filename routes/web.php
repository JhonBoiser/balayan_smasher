<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController;
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

// AUTHENTICATION
Auth::routes();

// CUSTOMER ROUTES (AUTH REQUIRED)
Route::middleware(['auth'])->group(function () {
    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
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
        // Customer actions on their orders
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{id}/payment', [OrderController::class, 'updatePaymentStatus'])->name('payment');
        Route::post('/{id}/send-email', [OrderController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/send-sms', [OrderController::class, 'sendSms'])->name('send-sms');
    });
});

// ADMIN ROUTES (AUTH + ADMIN MIDDLEWARE)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Search Routes - ADD THESE ROUTES
    Route::get('/search', [SearchController::class, 'globalSearch'])->name('search');
    Route::get('/search/advanced', [SearchController::class, 'advancedSearch'])->name('search.advanced');

    // Recent orders for notifications - ADD THIS ROUTE
    Route::get('/orders/recent', [SearchController::class, 'recentOrders'])->name('orders.recent');

    // Admin Products
    Route::resource('products', AdminProductController::class);
    // Match controller signature: deleteImage($id) expects the image id only
    Route::delete('products/image/{id}', [AdminProductController::class, 'deleteImage'])->name('products.image.delete');

    // Admin Categories
    Route::resource('categories', AdminCategoryController::class);

    // Admin Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{id}/check-updates', [AdminOrderController::class, 'checkUpdates'])->name('check-updates');

        // Remove this duplicate route since we added it above with SearchController
        // Route::get('/recent', [AdminOrderController::class, 'getRecentOrders'])->name('recent');

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

// FALLBACK ROUTE
Route::fallback(function () {
    return redirect()->route('login');
});
