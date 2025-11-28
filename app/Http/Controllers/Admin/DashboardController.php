<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    /**
     * Return revenue data for chart filtering (week, month, year)
     */
    public function revenueData(Request $request)
    {
        $range = $request->query('range', 'month'); // week|month|year
        $now = Carbon::now();

        if ($range === 'week') {
            $labels = [];
            $data = [];

            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $day = $now->copy()->subDays($i);
                $labels[] = $day->format('D');
                $start = $day->startOfDay()->toDateTimeString();
                $end = $day->endOfDay()->toDateTimeString();

                $sum = Order::where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('total');

                $data[] = (float) $sum;
            }

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        if ($range === 'year') {
            $labels = [];
            $data = [];

            // Months Jan..Dec for current year
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create($now->year, $m, 1)->format('M');
                $sum = Order::where('status', '!=', 'cancelled')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $m)
                    ->sum('total');
                $data[] = (float) $sum;
            }

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        // default: month (current month by day)
        $labels = [];
        $data = [];
        $daysInMonth = $now->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = (string) $d;
            $dayStart = Carbon::create($now->year, $now->month, $d)->startOfDay()->toDateTimeString();
            $dayEnd = Carbon::create($now->year, $now->month, $d)->endOfDay()->toDateTimeString();

            $sum = Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('total');

            $data[] = (float) $sum;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}
