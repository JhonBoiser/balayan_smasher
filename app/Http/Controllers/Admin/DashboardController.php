<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', DB::raw('low_stock_threshold'))
            ->where('stock', '>', 0)
            ->take(5)
            ->get();

        $pendingOrders = Order::where('status', 'pending')->count();

        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total');

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'lowStockProducts',
            'pendingOrders',
            'monthlyRevenue'
        ));
    }
}
