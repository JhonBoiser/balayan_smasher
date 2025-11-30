<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache frequently used data to improve performance
        $cacheTime = 300; // 5 minutes

        // Get basic statistics with caching - ONLY PAID AND DELIVERED ORDERS
        $totalOrders = cache()->remember('total_orders', $cacheTime, function () {
            return Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->count();
        });

        $totalRevenue = cache()->remember('total_revenue', $cacheTime, function () {
            $sum = Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->sum('total');
            // Ensure proper decimal precision
            return (float) $sum;
        });

        $totalProducts = cache()->remember('total_products', $cacheTime, function () {
            return Product::count();
        });

        $totalCustomers = cache()->remember('total_customers', $cacheTime, function () {
            return User::where('role', 'customer')->count();
        });

        // Get recent orders without caching (should be real-time)
        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get(['id', 'order_number', 'user_id', 'total', 'status', 'payment_status', 'created_at']);

        // Get low stock products - Fixed: use whereRaw for proper comparison
        $lowStockProducts = Product::whereRaw('stock <= low_stock_threshold')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get(['id', 'name', 'sku', 'stock', 'low_stock_threshold']);

        // Get pending orders count - FIXED: now correctly counts only pending status
        $pendingOrders = cache()->remember('pending_orders', 60, function () {
            return Order::where('status', 'pending')
                ->count();
        });

        // Get today's revenue for real-time display - ONLY PAID AND DELIVERED
        $todayRevenue = (float) Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        // Get revenue growth data - ONLY PAID AND DELIVERED
        $currentMonthRevenue = (float) Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = (float) Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : ($currentMonthRevenue > 0 ? 100 : 0);

        // Get top selling products for line graph - ONLY PAID AND DELIVERED ORDERS
        // FIXED: Corrected the subquery to properly sum quantities
        $topProducts = DB::table('products')
            ->select('products.id', 'products.name', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'lowStockProducts',
            'pendingOrders',
            'todayRevenue',
            'revenueGrowth',
            'topProducts'
        ));
    }

    /**
     * Return revenue data for line graph filtering (week, month, year)
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    public function revenueData(Request $request)
    {
        $range = $request->query('range', 'month'); // week|month|year|comparison
        $now = Carbon::now();

        // Don't cache real-time data, or use very short cache
        $cacheKey = "revenue_data_{$range}_{$now->format('Y_m_d_H_i')}"; // Cache per minute

        $data = cache()->remember($cacheKey, 60, function () use ($range, $now) {
            switch ($range) {
                case 'week':
                    return $this->getWeeklyRevenueData($now);
                case 'year':
                    return $this->getYearlyRevenueData($now);
                case 'comparison':
                    return $this->getRevenueComparisonData($now);
                default: // month
                    return $this->getMonthlyRevenueData($now);
            }
        });

        return response()->json($data);
    }

    /**
     * Get weekly revenue data for the last 7 days - LINE GRAPH
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    private function getWeeklyRevenueData($now)
    {
        $labels = [];
        $data = [];
        $totalRevenue = 0;

        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $labels[] = $day->format('D M j');
            $start = $day->startOfDay()->toDateTimeString();
            $end = $day->endOfDay()->toDateTimeString();

            $sum = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total');

            $data[] = round($sum, 2);
            $totalRevenue += $sum;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Daily Revenue',
                    'data' => $data,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ],
            'total' => round($totalRevenue, 2),
            'average' => round($totalRevenue / 7, 2),
            'type' => 'line',
            'title' => 'Weekly Revenue Trend (Paid & Delivered Orders)'
        ];
    }

    /**
     * Get monthly revenue data for current month - LINE GRAPH
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    private function getMonthlyRevenueData($now)
    {
        $labels = [];
        $data = [];
        $totalRevenue = 0;
        $daysInMonth = $now->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = "Day " . $d;
            $dayStart = Carbon::create($now->year, $now->month, $d)->startOfDay()->toDateTimeString();
            $dayEnd = Carbon::create($now->year, $now->month, $d)->endOfDay()->toDateTimeString();

            $sum = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('total');

            $data[] = round($sum, 2);
            $totalRevenue += $sum;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Daily Revenue',
                    'data' => $data,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ],
            'total' => round($totalRevenue, 2),
            'average' => round($totalRevenue / $daysInMonth, 2),
            'type' => 'line',
            'title' => 'Monthly Revenue Trend (Paid & Delivered Orders)'
        ];
    }

    /**
     * Get yearly revenue data for current year - LINE GRAPH
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    private function getYearlyRevenueData($now)
    {
        $labels = [];
        $data = [];
        $totalRevenue = 0;

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create($now->year, $m, 1)->format('M Y');
            $sum = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $m)
                ->sum('total');
            $data[] = round($sum, 2);
            $totalRevenue += $sum;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Monthly Revenue',
                    'data' => $data,
                    'borderColor' => '#dc3545',
                    'backgroundColor' => 'rgba(220, 53, 69, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ],
            'total' => round($totalRevenue, 2),
            'average' => round($totalRevenue / 12, 2),
            'type' => 'line',
            'title' => 'Yearly Revenue Trend (Paid & Delivered Orders)'
        ];
    }

    /**
     * Get revenue comparison data (current vs previous period) - MULTI-LINE GRAPH
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    private function getRevenueComparisonData($now)
    {
        $labels = [];
        $currentData = [];
        $previousData = [];
        $totalCurrent = 0;
        $totalPrevious = 0;

        // Current month vs previous month comparison
        $daysInMonth = $now->daysInMonth;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = "Day " . $d;

            // Current month data
            $currentDayStart = Carbon::create($now->year, $now->month, $d)->startOfDay();
            $currentDayEnd = $currentDayStart->copy()->endOfDay();

            $currentSum = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$currentDayStart, $currentDayEnd])
                ->sum('total');
            $currentData[] = round($currentSum, 2);
            $totalCurrent += $currentSum;

            // Previous month data
            $prevMonthDay = $currentDayStart->copy()->subMonth();
            $prevSum = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [
                    $prevMonthDay->startOfDay(),
                    $prevMonthDay->endOfDay()
                ])
                ->sum('total');
            $previousData[] = round($prevSum, 2);
            $totalPrevious += $prevSum;
        }

        $growth = $totalPrevious > 0
            ? (($totalCurrent - $totalPrevious) / $totalPrevious) * 100
            : ($totalCurrent > 0 ? 100 : 0);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Current Month',
                    'data' => $currentData,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'fill' => false,
                    'tension' => 0.4
                ],
                [
                    'label' => 'Previous Month',
                    'data' => $previousData,
                    'borderColor' => '#6c757d',
                    'backgroundColor' => 'rgba(108, 117, 125, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderDash' => [5, 5]
                ]
            ],
            'total' => round($totalCurrent, 2),
            'previous_total' => round($totalPrevious, 2),
            'growth' => round($growth, 2),
            'type' => 'line',
            'title' => 'Revenue Comparison: Current vs Previous Month (Paid & Delivered Orders)'
        ];
    }

    /**
     * Get real-time dashboard stats for AJAX updates
     * ONLY INCLUDES PAID AND DELIVERED ORDERS FOR REVENUE
     */
    public function realTimeStats(Request $request)
    {
        // Get real-time data without caching for immediate updates
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = (float) Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();

        // Get current active users (last 5 minutes)
        $activeUsers = User::where('role', 'customer')
            ->where('last_active_at', '>=', now()->subMinutes(5))
            ->count();

        return response()->json([
            'today_orders' => $todayOrders,
            'today_revenue' => round($todayRevenue, 2),
            'pending_orders' => $pendingOrders,
            'active_users' => $activeUsers,
            'updated_at' => now()->format('h:i:s A')
        ]);
    }

    /**
     * Get real-time sales data for the current day (hourly) - LINE GRAPH
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    public function realTimeSalesData(Request $request)
    {
        $today = now();
        $labels = [];
        $data = [];
        $totalRevenue = 0;

        for ($i = 0; $i < 24; $i++) {
            $hour = $today->copy()->startOfDay()->addHours($i);
            $labels[] = $hour->format('H:i');

            $hourlySales = (float) Order::where('status', 'delivered')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [
                    $hour->toDateTimeString(),
                    $hour->copy()->addHour()->toDateTimeString()
                ])
                ->sum('total');

            $data[] = round($hourlySales, 2);
            $totalRevenue += $hourlySales;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hourly Revenue',
                    'data' => $data,
                    'borderColor' => '#ff6b00',
                    'backgroundColor' => 'rgba(255, 107, 0, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ],
            'total' => round($totalRevenue, 2),
            'type' => 'line',
            'title' => 'Today\'s Hourly Sales (Paid & Delivered Orders)'
        ]);
    }

    /**
     * Get sales performance comparison for different periods
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    public function salesComparison(Request $request)
    {
        $period = $request->query('period', 'month'); // month|quarter|year

        $currentData = [];
        $previousData = [];
        $labels = [];

        switch ($period) {
            case 'month':
                $currentData = $this->getMonthlyRevenueData(now());
                $previousData = $this->getMonthlyRevenueData(now()->subMonth());
                $labels = $currentData['labels'];
                break;

            case 'year':
                $currentData = $this->getYearlyRevenueData(now());
                $previousData = $this->getYearlyRevenueData(now()->subYear());
                $labels = $currentData['labels'];
                break;
        }

        return response()->json([
            'labels' => $labels,
            'current' => $currentData['datasets'][0]['data'],
            'previous' => $previousData['datasets'][0]['data'],
            'current_total' => $currentData['total'],
            'previous_total' => $previousData['total'],
            'growth' => $previousData['total'] > 0
                ? (($currentData['total'] - $previousData['total']) / $previousData['total']) * 100
                : 0,
            'type' => 'line'
        ]);
    }

    /**
     * Get order status data for bar chart (better for status distribution)
     */
    public function orderStatusData(Request $request)
    {
        $statusDistribution = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'pending' => '#ffc107',
            'processing' => '#17a2b8',
            'completed' => '#28a745',
            'shipped' => '#007bff',
            'delivered' => '#28a745',
            'cancelled' => '#dc3545',
            'refunded' => '#6c757d'
        ];

        $backgroundColors = [];
        $borderColors = [];

        foreach ($statusDistribution as $status) {
            $labels[] = ucfirst($status->status);
            $data[] = $status->count;
            $backgroundColors[] = $colors[$status->status] ?? '#6c757d';
            $borderColors[] = $colors[$status->status] ?? '#6c757d';
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Order Count',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1
                ]
            ],
            'total' => array_sum($data),
            'type' => 'bar',
            'title' => 'Order Status Distribution'
        ]);
    }

    /**
     * Get revenue statistics for dashboard
     * ONLY INCLUDES PAID AND DELIVERED ORDERS
     */
    public function getRevenueStatistics()
    {
        try {
            $stats = [
                'total_revenue' => (float) round(Order::where('status', 'delivered')
                    ->where('payment_status', 'paid')
                    ->sum('total'), 2),
                'today_revenue' => (float) round(Order::where('status', 'delivered')
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', today())
                    ->sum('total'), 2),
                'month_revenue' => (float) round(Order::where('status', 'delivered')
                    ->where('payment_status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total'), 2),
                'year_revenue' => (float) round(Order::where('status', 'delivered')
                    ->where('payment_status', 'paid')
                    ->whereYear('created_at', now()->year)
                    ->sum('total'), 2),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Revenue statistics error: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
