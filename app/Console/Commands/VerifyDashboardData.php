<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class VerifyDashboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:verify {--fix : Automatically fix cache issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify dashboard tallies match the real-time database and fix any discrepancies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('Dashboard Data Verification');
        $this->info('========================================');

        // Get real database values
        $dbTotalOrders = Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->count();

        $dbTotalRevenue = (float) Order::where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->sum('total');

        $dbPendingOrders = Order::where('status', 'pending')
            ->count();

        // Get cached values
        $cacheTotalOrders = Cache::get('total_orders', null);
        $cacheTotalRevenue = Cache::get('total_revenue', null);
        $cachePendingOrders = Cache::get('pending_orders', null);

        // Display verification results
        $this->line('');
        $this->info('TOTAL ORDERS (Paid & Delivered):');
        $this->line("  Database: {$dbTotalOrders}");
        $this->line("  Cache:    " . ($cacheTotalOrders ?? 'NOT CACHED'));
        $this->line("  Status:   " . ($dbTotalOrders == $cacheTotalOrders ? '✓ OK' : '✗ MISMATCH'));

        $this->line('');
        $this->info('TOTAL REVENUE (Paid & Delivered):');
        $this->line("  Database: ₱" . number_format($dbTotalRevenue, 2));
        $this->line("  Cache:    ₱" . number_format($cacheTotalRevenue ?? 0, 2));
        $this->line("  Status:   " . ($dbTotalRevenue == ($cacheTotalRevenue ?? 0) ? '✓ OK' : '✗ MISMATCH'));

        $this->line('');
        $this->info('PENDING ORDERS:');
        $this->line("  Database: {$dbPendingOrders}");
        $this->line("  Cache:    " . ($cachePendingOrders ?? 'NOT CACHED'));
        $this->line("  Status:   " . ($dbPendingOrders == $cachePendingOrders ? '✓ OK' : '✗ MISMATCH'));

        // If fix option is set, clear caches
        if ($this->option('fix')) {
            $this->line('');
            $this->info('Clearing caches...');
            Order::clearDashboardCache();
            Cache::clear();
            $this->info('✓ All caches cleared successfully!');
        }

        $this->line('');
        $this->info('========================================');
        $this->info('Verification Complete');
        $this->info('========================================');

        return 0;
    }
}
