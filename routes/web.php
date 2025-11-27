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
// Add this to your web.php routes file
Route::get('/about', function () {
    return view('about');
})->name('about');
// Add this to your web.php routes file
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
    });
});

// ADMIN ROUTES (AUTH + ADMIN MIDDLEWARE)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Search Routes
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'globalSearch'])->name('global');
        Route::get('/advanced', [SearchController::class, 'advancedSearch'])->name('advanced');
    });

    // Admin Products
    Route::resource('products', AdminProductController::class);
    Route::delete('products/{id}/image/{imageId}', [AdminProductController::class, 'deleteImage'])->name('products.image.delete');

    // Admin Categories
    Route::resource('categories', AdminCategoryController::class);

    // Admin Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{id}/check-updates', [AdminOrderController::class, 'checkUpdates'])->name('check-updates');
        Route::get('/recent', [AdminOrderController::class, 'getRecentOrders'])->name('recent'); // ADDED THIS LINE
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
