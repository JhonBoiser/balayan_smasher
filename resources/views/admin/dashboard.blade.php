{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Orders</h6>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                    <small class="text-muted">Paid & Delivered</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Revenue</h6>
                    <h3 class="mb-0">₱{{ number_format($totalRevenue, 2) }}</h3>
                    <small class="text-muted">Paid & Delivered</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Products</h6>
                    <h3 class="mb-0">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Customers</h6>
                    <h3 class="mb-0">{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Pending Orders</h6>
                        <h2 class="text-danger mb-0">{{ $pendingOrders }}</h2>
                        <small class="text-muted">Awaiting processing</small>
                    </div>
                    <i class="fas fa-clock text-danger" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Revenue Overview</h6>
                    <small class="text-muted">Paid & Delivered Orders Only</small>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Revenue filter" id="revenueFilter">
                    <button type="button" class="btn btn-outline-secondary" data-range="week">Week</button>
                    <button type="button" class="btn btn-outline-secondary active" data-range="month">Month</button>
                    <button type="button" class="btn btn-outline-secondary" data-range="year">Year</button>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="text-muted mb-1">Selected Period Revenue</h6>
                        <h3 class="mb-0 text-success" id="selectedRevenue">₱0.00</h3>
                        <small class="text-muted">Paid & Delivered</small>
                    </div>
                    <i class="fas fa-chart-pie text-success" style="font-size: 2rem;"></i>
                </div>

                <div class="chart-container" style="position: relative; height: 200px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>₱{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning">Manage</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($lowStockProducts as $product)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                            </div>
                            <span class="badge bg-danger">{{ $product->stock }} left</span>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                        All products are well stocked!
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    let revenueChart = null;

    function formatCurrency(amount) {
        return '₱' + Number(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function destroyChart() {
        if (revenueChart) {
            revenueChart.destroy();
            revenueChart = null;
        }
    }

    function createLineChart(data) {
        destroyChart();

        const config = {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += formatCurrency(context.parsed.y);
                                return label;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: data.title,
                        font: {
                            size: 14
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                },
                elements: {
                    point: {
                        radius: 3,
                        hoverRadius: 6
                    }
                }
            }
        };

        revenueChart = new Chart(ctx, config);
    }

    async function fetchRevenue(range = 'month') {
        try {
            const url = `{{ route('admin.dashboard.revenue-data') }}?range=${range}`;
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            const json = await res.json();

            document.getElementById('selectedRevenue').textContent = formatCurrency(json.total);

            createLineChart(json);

        } catch (err) {
            console.error('Failed to fetch revenue data:', err);
            document.getElementById('selectedRevenue').textContent = formatCurrency(0);

            // Show error state in chart
            createLineChart({
                labels: ['Error'],
                datasets: [{
                    label: 'Error Loading Data',
                    data: [0],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true
                }],
                total: 0,
                title: 'Error Loading Revenue Data'
            });
        }
    }

    // Wire up filter buttons
    document.getElementById('revenueFilter')?.addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-range]');
        if (!btn) return;

        document.querySelectorAll('#revenueFilter button').forEach(b => {
            b.classList.remove('active');
        });

        btn.classList.add('active');
        const range = btn.dataset.range;
        fetchRevenue(range);
    });

    // Initial load
    const activeBtn = document.querySelector('#revenueFilter button.active');
    const initialRange = activeBtn ? activeBtn.dataset.range : 'month';
    fetchRevenue(initialRange);

    // Real-time data refresh (every 30 seconds)
    setInterval(function() {
        const currentRange = document.querySelector('#revenueFilter button.active')?.dataset.range || 'month';
        fetchRevenue(currentRange);
    }, 30000);

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (revenueChart) {
                revenueChart.resize();
            }
        }, 250);
    });
});
</script>
@endsection
