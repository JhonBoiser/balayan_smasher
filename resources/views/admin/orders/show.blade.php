{{-- ========================================== --}}
{{-- resources/views/admin/orders/show.blade.php --}}
{{-- FULLY RESPONSIVE WITH REAL-TIME UPDATES --}}
{{-- ========================================== --}}
@extends('layouts.admin')

@section('page-title', 'Order Details')

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -32px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: white;
        z-index: 1;
        transition: all 0.3s;
    }
    .timeline-marker.active {
        background: #28a745;
        animation: pulse 2s infinite;
    }
    .timeline-marker.inactive {
        background: #6c757d;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        body { padding: 20px; }
    }

    /* Mobile Responsive Styles */
    @media (max-width: 991px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .order-header > div:first-child {
            margin-bottom: 15px;
        }
        .order-header .btn-group {
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 10px;
        }
        .order-header .btn {
            flex: 1;
        }

        .timeline {
            padding-left: 30px;
        }
        .timeline::before {
            left: 10px;
        }
        .timeline-marker {
            left: -24px;
            width: 25px;
            height: 25px;
            font-size: 10px;
        }

        .product-mobile {
            display: block !important;
        }
        .product-mobile td {
            display: block;
            text-align: left !important;
            border: none !important;
            padding: 5px 10px !important;
        }
        .product-mobile td:first-child {
            padding-top: 15px !important;
        }
        .product-mobile td:last-child {
            padding-bottom: 15px !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .mobile-sticky-actions {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        body {
            padding-bottom: 70px;
        }
    }

    /* Real-time update animations */
    .update-flash {
        animation: flashBg 1s ease-in-out;
    }

    @keyframes flashBg {
        0%, 100% { background-color: transparent; }
        50% { background-color: #ffd70033; }
    }

    .status-updating {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 order-header">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-receipt"></i> Order #<span id="orderNumber">{{ $order->order_number }}</span>
        </h2>
        <small class="text-muted" id="orderDate">{{ $order->created_at->format('F d, Y h:i A') }}</small>
        <br>
        <small class="text-muted">Last updated: <span id="lastUpdated">{{ $order->updated_at->diffForHumans() }}</span></small>
    </div>
    <div class="no-print">
        <div class="btn-group" role="group">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Print</span>
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back</span>
            </a>
        </div>
    </div>
</div>

<!-- Real-time Status Alert -->
<div id="realtimeAlert" class="alert alert-info d-none" role="alert">
    <i class="bi bi-arrow-repeat loading-spinner"></i>
    <span id="alertMessage">Checking for updates...</span>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Order Status Timeline -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Order Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline" id="orderTimeline">
                    <div class="timeline-item">
                        <div class="timeline-marker {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : 'inactive' }}">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : 'inactive' }}">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Processing</h6>
                                <small class="text-muted">
                                    <span id="processingTime">
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @else
                                            Pending
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : 'inactive' }}">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Shipped</h6>
                                <small class="text-muted">
                                    <span id="shippedTime">
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @else
                                            Pending
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status == 'delivered' ? 'active' : 'inactive' }}">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Delivered</h6>
                                <small class="text-muted">
                                    <span id="deliveredTime">
                                        @if($order->status == 'delivered')
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @else
                                            Pending
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-cart"></i> Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light d-none d-md-table-header-group">
                            <tr>
                                <th style="width: 80px;">Image</th>
                                <th>Product</th>
                                <th style="width: 100px;">Price</th>
                                <th style="width: 80px;">Qty</th>
                                <th style="width: 120px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="orderItems">
                            @foreach($order->items as $item)
                            <tr class="product-mobile">
                                <td>
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}"
                                             alt="{{ $item->product_name }}"
                                             class="img-thumbnail"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                        @if($item->product)
                                            <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="d-md-none text-muted">Price: </span>
                                    ₱{{ number_format($item->price, 2) }}
                                </td>
                                <td>
                                    <span class="d-md-none text-muted">Qty: </span>
                                    {{ $item->quantity }}
                                </td>
                                <td class="text-md-end">
                                    <span class="d-md-none text-muted">Subtotal: </span>
                                    <strong>₱{{ number_format($item->subtotal, 2) }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end"><strong id="orderSubtotal">₱{{ number_format($order->subtotal, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Shipping Fee:</strong></td>
                                <td class="text-end"><strong id="orderShipping">₱{{ number_format($order->shipping_fee, 2) }}</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                <td class="text-end"><h5 class="mb-0 text-primary" id="orderTotal">₱{{ number_format($order->total, 2) }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Information -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Customer</h5>
                    </div>
                    <div class="card-body" id="customerInfo">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 80px;">Name:</td>
                                <td><strong>{{ $order->user->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td><small>{{ $order->user->email }}</small></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone:</td>
                                <td>{{ $order->user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">ID:</td>
                                <td>#{{ str_pad($order->user->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Shipping</h5>
                    </div>
                    <div class="card-body" id="shippingInfo">
                        <address class="mb-0" style="font-size: 0.95rem;">
                            <strong>{{ $order->shipping_name }}</strong><br>
                            {{ $order->shipping_phone }}<br>
                            <small class="text-muted">{{ $order->shipping_email }}</small><br><br>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_province }}<br>
                            {{ $order->shipping_zipcode }}
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-journal-text"></i> Order Notes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted" id="orderNotes">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow-sm mb-4 no-print d-none d-lg-block">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#emailModal">
                        <i class="bi bi-envelope"></i> Send Email
                    </button>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#smsModal">
                        <i class="bi bi-phone"></i> Send SMS
                    </button>
                    <button class="btn btn-outline-info" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Invoice
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-flag"></i> Update Status</h5>
            </div>
            <div class="card-body">
                <form id="statusUpdateForm">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <select name="status" id="statusSelect" class="form-select form-select-lg">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>📋 Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                        <small class="text-muted">Customer will be notified</small>
                    </div>

                    <div class="mb-3" id="trackingNumberField" style="display: {{ in_array($order->status, ['pending', 'processing']) ? 'block' : 'none' }};">
                        <label class="form-label">Tracking # (Optional)</label>
                        <input type="text" name="tracking_number" id="trackingNumber" class="form-control" placeholder="Enter tracking number" value="{{ $order->tracking_number ?? '' }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="statusUpdateBtn">
                        <i class="bi bi-check-circle"></i> Update & Notify
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="card shadow-sm mb-4 no-print">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-3">
                    <tr>
                        <td class="text-muted">Method:</td>
                        <td class="text-end">
                            <span class="badge bg-info" id="paymentMethod">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td class="text-end">
                            <span class="badge" id="paymentStatusBadge">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <form id="paymentUpdateForm">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Update Payment</label>
                        <select name="payment_status" id="paymentStatusSelect" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100" id="paymentUpdateBtn">
                        <i class="bi bi-cash-coin"></i> Update Payment
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Order ID:</td>
                        <td class="text-end"><code>#{{ $order->id }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Order #:</td>
                        <td class="text-end"><strong>{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date:</td>
                        <td class="text-end">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Time:</td>
                        <td class="text-end">{{ $order->created_at->format('h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Items:</td>
                        <td class="text-end"><strong>{{ $order->items->sum('quantity') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Updated:</td>
                        <td class="text-end"><span id="summaryUpdated">{{ $order->updated_at->diffForHumans() }}</span></td>
                    </tr>
                </table>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="h6 mb-0">Total:</span>
                    <span class="h5 mb-0 text-primary">₱{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sticky Action Buttons -->
<div class="mobile-sticky-actions no-print d-lg-none">
    <div class="d-flex gap-2">
        <button class="btn btn-primary flex-fill" data-bs-toggle="modal" data-bs-target="#emailModal">
            <i class="bi bi-envelope"></i> Email
        </button>
        <button class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#smsModal">
            <i class="bi bi-phone"></i> SMS
        </button>
        <button class="btn btn-info flex-fill" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email to Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="emailForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="text" class="form-control" value="{{ $order->user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="emailSubject" class="form-control" value="Update on Order #{{ $order->order_number }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" id="emailMessage" class="form-control" rows="5" required placeholder="Enter your message to the customer..."></textarea>
                    </div>
                    <div class="alert alert-info mb-3">
                        <small><i class="bi bi-info-circle"></i> Email will be sent from {{ config('mail.from.address') }}</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="sendEmailBtn">
                        <i class="bi bi-send"></i> Send Email
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send SMS to Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="smsForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" value="{{ $order->shipping_phone }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" id="smsMessage" class="form-control" rows="4" required placeholder="Enter SMS message..." maxlength="160"></textarea>
                        <small class="text-muted" id="charCounter">0/160 characters</small>
                    </div>
                    <div class="alert alert-info mb-3">
                        <small><i class="bi bi-info-circle"></i> SMS will be prefixed with "Balayan Smashers Hub:" and order number</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100" id="sendSmsBtn">
                        <i class="bi bi-phone"></i> Send SMS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ===========================================
// REAL-TIME DATA INTEGRATION
// ===========================================
const orderId = {{ $order->id }};
let lastUpdateTime = '{{ $order->updated_at }}';
const updateInterval = 30000; // Check every 30 seconds

// Check for updates periodically
setInterval(checkForUpdates, updateInterval);

async function checkForUpdates() {
    try {
        const response = await fetch(`/admin/orders/${orderId}/check-updates`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();

            if (data.updated && data.updated_at !== lastUpdateTime) {
                showUpdateAlert('Order has been updated. Refreshing data...');
                lastUpdateTime = data.updated_at;
                updateOrderDisplay(data.order);
            }
        }
    } catch (error) {
        console.error('Update check failed:', error);
    }
}

function updateOrderDisplay(order) {
    // Update status badge
    const statusBadges = {
        'pending': 'warning',
        'processing': 'info',
        'shipped': 'primary',
        'delivered': 'success',
        'cancelled': 'danger'
    };

    // Update timeline
    updateTimeline(order.status);

    // Update payment status
    const paymentBadge = document.getElementById('paymentStatusBadge');
    if (paymentBadge) {
        paymentBadge.className = `badge bg-${order.payment_status === 'paid' ? 'success' : (order.payment_status === 'failed' ? 'danger' : 'warning')}`;
        paymentBadge.textContent = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1);
    }

    // Update last updated time
    document.getElementById('lastUpdated').textContent = 'Just now';
    document.getElementById('summaryUpdated').textContent = 'Just now';

    // Flash animation
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('update-flash');
        setTimeout(() => card.classList.remove('update-flash'), 1000);
    });
}

function updateTimeline(status) {
    const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
    const currentIndex = statusOrder.indexOf(status);

    document.querySelectorAll('.timeline-marker').forEach((marker, index) => {
        if (index <= currentIndex) {
            marker.classList.remove('inactive');
            marker.classList.add('active');
        } else {
            marker.classList.remove('active');
            marker.classList.add('inactive');
        }
    });
}

function showUpdateAlert(message) {
    const alert = document.getElementById('realtimeAlert');
    const alertMessage = document.getElementById('alertMessage');

    alert.classList.remove('d-none');
    alertMessage.textContent = message;

    setTimeout(() => {
        alert.classList.add('d-none');
    }, 3000);
}

// ===========================================
// STATUS UPDATE FORM - AJAX SUBMISSION
// ===========================================
document.getElementById('statusUpdateForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('statusUpdateBtn');
    const originalText = btn.innerHTML;
    const statusSelect = document.getElementById('statusSelect');
    const trackingNumber = document.getElementById('trackingNumber').value;

    // Get selected status text
    const selectedOption = statusSelect.options[statusSelect.selectedIndex];
    const newStatus = selectedOption.text;

    if (!confirm(`Change order status to ${newStatus}? Customer will be notified via email and SMS.`)) {
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Updating...';

    try {
        const formData = new FormData(this);

        const response = await fetch(`/admin/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showUpdateAlert('✅ Order status updated successfully! Customer notified.');
            updateOrderDisplay(data.order);

            // Show/hide tracking number field based on status
            const trackingField = document.getElementById('trackingNumberField');
            if (['pending', 'processing'].includes(formData.get('status'))) {
                trackingField.style.display = 'block';
            } else {
                trackingField.style.display = 'none';
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
        }
    } catch (error) {
        console.error('Status update error:', error);
        alert('Failed to update status. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// ===========================================
// PAYMENT UPDATE FORM - AJAX SUBMISSION
// ===========================================
document.getElementById('paymentUpdateForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('paymentUpdateBtn');
    const originalText = btn.innerHTML;
    const paymentSelect = document.getElementById('paymentStatusSelect');
    const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
    const newStatus = selectedOption.text;

    if (!confirm(`Change payment status to ${newStatus}?`)) {
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Updating...';

    try {
        const formData = new FormData(this);

        const response = await fetch(`/admin/orders/${orderId}/payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showUpdateAlert('✅ Payment status updated successfully!');

            // Update payment badge
            const badge = document.getElementById('paymentStatusBadge');
            const status = formData.get('payment_status');
            badge.className = `badge bg-${status === 'paid' ? 'success' : (status === 'failed' ? 'danger' : 'warning')}`;
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        } else {
            alert('Error: ' + (data.message || 'Failed to update payment status'));
        }
    } catch (error) {
        console.error('Payment update error:', error);
        alert('Failed to update payment status. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// ===========================================
// EMAIL FORM - AJAX SUBMISSION
// ===========================================
document.getElementById('emailForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('sendEmailBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Sending...';

    try {
        const formData = new FormData(this);

        const response = await fetch(`/admin/orders/${orderId}/send-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showUpdateAlert('✅ Email sent successfully to {{ $order->user->email }}');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
            modal.hide();

            // Reset form
            this.reset();
            document.getElementById('emailSubject').value = 'Update on Order #{{ $order->order_number }}';
        } else {
            alert('Error: ' + (data.message || 'Failed to send email'));
        }
    } catch (error) {
        console.error('Email send error:', error);
        alert('Failed to send email. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// ===========================================
// SMS FORM - AJAX SUBMISSION
// ===========================================
document.getElementById('smsForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const message = document.getElementById('smsMessage').value;
    if (message.length > 160) {
        alert('SMS message cannot exceed 160 characters');
        return;
    }

    const btn = document.getElementById('sendSmsBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Sending...';

    try {
        const formData = new FormData(this);

        const response = await fetch(`/admin/orders/${orderId}/send-sms`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showUpdateAlert('✅ SMS sent successfully to {{ $order->shipping_phone }}');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('smsModal'));
            modal.hide();

            // Reset form
            this.reset();
            document.getElementById('charCounter').textContent = '0/160 characters';
        } else {
            alert('Error: ' + (data.message || 'Failed to send SMS'));
        }
    } catch (error) {
        console.error('SMS send error:', error);
        alert('Failed to send SMS. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// ===========================================
// SMS CHARACTER COUNTER
// ===========================================
const smsTextarea = document.getElementById('smsMessage');
const charCounter = document.getElementById('charCounter');

if (smsTextarea && charCounter) {
    smsTextarea.addEventListener('input', function() {
        const maxLength = 160;
        const currentLength = this.value.length;
        charCounter.textContent = `${currentLength}/${maxLength} characters`;

        if (currentLength > maxLength) {
            charCounter.classList.add('text-danger');
            charCounter.classList.remove('text-muted', 'text-warning');
        } else if (currentLength > 140) {
            charCounter.classList.add('text-warning');
            charCounter.classList.remove('text-muted', 'text-danger');
        } else {
            charCounter.classList.add('text-muted');
            charCounter.classList.remove('text-danger', 'text-warning');
        }
    });
}

// ===========================================
// SHOW/HIDE TRACKING NUMBER FIELD
// ===========================================
document.getElementById('statusSelect').addEventListener('change', function() {
    const trackingField = document.getElementById('trackingNumberField');
    if (['pending', 'processing'].includes(this.value)) {
        trackingField.style.display = 'block';
    } else {
        trackingField.style.display = 'none';
    }
});

// ===========================================
// AUTO-HIDE STICKY ACTIONS ON SCROLL
// ===========================================
let lastScrollTop = 0;
const stickyActions = document.querySelector('.mobile-sticky-actions');

if (stickyActions) {
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            // Scrolling down
            stickyActions.style.transform = 'translateY(0)';
        } else {
            // Scrolling up
            if (scrollTop < 100) {
                stickyActions.style.transform = 'translateY(0)';
            }
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
}

// ===========================================
// PAGE VISIBILITY API - CHECK UPDATES WHEN TAB ACTIVE
// ===========================================
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        // Tab became active, check for updates
        checkForUpdates();
    }
});

// Initial check on page load
window.addEventListener('load', function() {
    console.log('Order page loaded. Real-time updates enabled.');
});
</script>
@endsection
