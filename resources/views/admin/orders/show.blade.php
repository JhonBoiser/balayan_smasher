{{-- resources/views/admin/orders/show.blade.php --}}
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
        transition: all 0.5s ease-in-out;
        transform: scale(1);
    }
    .timeline-marker.active {
        background: #28a745;
        animation: pulseActive 2s infinite;
    }
    .timeline-marker.inactive {
        background: #6c757d;
    }
    .timeline-marker.cancelled {
        background: #dc3545;
    }

    @keyframes pulseActive {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }
    }

    .timeline-item .card {
        transition: all 0.3s ease-in-out;
    }

    .timeline-update {
        animation: highlightCard 1s ease-in-out;
    }

    @keyframes highlightCard {
        0% { background-color: rgba(40, 167, 69, 0.1); }
        100% { background-color: transparent; }
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
                        <div class="timeline-marker {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : ($order->status == 'cancelled' ? 'cancelled' : 'inactive') }}">
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
                        <div class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : ($order->status == 'cancelled' ? 'cancelled' : 'inactive') }}">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Processing</h6>
                                <small class="text-muted">
                                    <span id="processingTime">
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @elseif($order->status == 'cancelled')
                                            Cancelled
                                        @else
                                            Pending
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : ($order->status == 'cancelled' ? 'cancelled' : 'inactive') }}">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Shipped</h6>
                                <small class="text-muted">
                                    <span id="shippedTime">
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @elseif($order->status == 'cancelled')
                                            Cancelled
                                        @else
                                            Pending
                                        @endif
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status == 'delivered' ? 'active' : ($order->status == 'cancelled' ? 'cancelled' : 'inactive') }}">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h6 class="mb-0">Delivered</h6>
                                <small class="text-muted">
                                    <span id="deliveredTime">
                                        @if($order->status == 'delivered')
                                            {{ $order->updated_at->format('M d, Y h:i A') }}
                                        @elseif($order->status == 'cancelled')
                                            Cancelled
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
                                        <img src="{{ $item->product->getImageUrl($item->product->primaryImage->image_path) }}"
                                             alt="{{ $item->product_name }}"
                                             class="img-thumbnail"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/60?text=No+Image'">
                                    @elseif($item->product)
                                        <img src="{{ $item->product->getDisplayImageUrl() }}"
                                             alt="{{ $item->product_name }}"
                                             class="img-thumbnail"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/60?text=No+Image'">
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
            <input type="hidden" name="_method" value="PATCH">
            <div class="mb-3">
                <label class="form-label">Current Status</label>
                <select name="status" id="statusSelect" class="form-select form-select-lg">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>📋 Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                </select>
                <small class="text-muted">Customer will be notified via email</small>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="statusUpdateBtn">
                <i class="bi bi-check-circle"></i> Update Status & Notify Customer
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
                    <span class="badge" id="paymentStatusBadge"
                        @if($order->payment_status == 'paid')
                            style="background-color: #28a745;"
                        @elseif($order->payment_status == 'failed')
                            style="background-color: #dc3545;"
                        @else
                            style="background-color: #ffc107;"
                        @endif
                    >
                        @if($order->payment_status == 'paid')
                            ✅ Paid
                        @elseif($order->payment_status == 'failed')
                            ❌ Failed
                        @elseif($order->payment_status == 'refunded')
                            🔄 Refunded
                        @else
                            ⏳ Pending
                        @endif
                    </span>
                </td>
            </tr>
        </table>

        <form id="paymentUpdateForm">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <div class="mb-3">
                <label class="form-label">Update Payment Status</label>
                <select name="payment_status" id="paymentStatusSelect" class="form-select">
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>🔄 Refunded</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100" id="paymentUpdateBtn">
                <i class="bi bi-cash-coin"></i> Update Payment Status
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
                        <input type="text" id="emailRecipient" class="form-control" value="{{ $order->user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" id="emailSubject" class="form-control" value="Update on Order #{{ $order->order_number }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea id="emailMessage" class="form-control" rows="5" required placeholder="Enter your message to the customer..."></textarea>
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
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" id="smsRecipient" class="form-control" value="{{ $order->shipping_phone }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea id="smsMessage" class="form-control" rows="4" required placeholder="Enter SMS message..." maxlength="160"></textarea>
                    <small class="text-muted" id="charCounter">0/160 characters</small>
                </div>
                <div class="alert alert-info mb-3">
                    <small><i class="bi bi-info-circle"></i> SMS will be prefixed with "Balayan Smashers Hub:" and order number</small>
                </div>
                <button type="button" class="btn btn-success w-100" id="sendSmsBtn" onclick="sendCustomSms()">
                    <i class="bi bi-phone"></i> Send SMS
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ===========================================
// REAL-TIME DATA INTEGRATION
// ===========================================
const orderId = {{ $order->id }};
let lastUpdateTime = '{{ $order->updated_at }}';
const updateInterval = 30000;

// Route URLs using Laravel helpers
const routes = {
    sendEmail: '{{ route("admin.orders.send-email", $order->id) }}',
    sendSms: '{{ route("admin.orders.send-sms", $order->id) }}',
    checkUpdates: '{{ route("admin.orders.check-updates", $order->id) }}',
    updateStatus: '{{ route("admin.orders.status", $order->id) }}',
    updatePayment: '{{ route("admin.orders.payment", $order->id) }}'
};

console.log('✅ Real-time Order Timeline initialized. Auto-refresh every 30 seconds.');

// Check for updates periodically
setInterval(checkForUpdates, updateInterval);

async function checkForUpdates() {
    try {
        const response = await fetch(routes.checkUpdates, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();

            // Check if order status has changed
            if (data.order && data.order.status) {
                const currentStatus = document.getElementById('statusSelect').value;

                if (data.order.status !== currentStatus) {
                    console.log('✅ Order updated! Status changed to:', data.order.status);
                    showUpdateAlert(`✅ Order status changed to: <strong>${data.order.status.toUpperCase()}</strong>`);

                    // Update the timeline
                    updateTimeline(data.order.status, data.updated_at);

                    // Update status select
                    document.getElementById('statusSelect').value = data.order.status;

                    // Update last updated time
                    document.getElementById('lastUpdated').textContent = 'Just now';
                    document.getElementById('summaryUpdated').textContent = 'Just now';

                    lastUpdateTime = data.updated_at;
                }

                // Update payment status if changed
                if (data.order.payment_status) {
                    const currentPaymentStatus = document.getElementById('paymentStatusSelect').value;
                    if (data.order.payment_status !== currentPaymentStatus) {
                        console.log('💳 Payment status changed to:', data.order.payment_status);
                        showUpdateAlert(`💳 Payment status changed to: <strong>${data.order.payment_status.toUpperCase()}</strong>`);

                        // Update payment select and badge
                        document.getElementById('paymentStatusSelect').value = data.order.payment_status;
                        const badge = document.getElementById('paymentStatusBadge');
                        const statusClass = data.order.payment_status === 'paid' ? 'success' :
                                          (data.order.payment_status === 'failed' ? 'danger' : 'warning');
                        badge.className = `badge bg-${statusClass}`;
                        badge.textContent = data.order.payment_status.charAt(0).toUpperCase() + data.order.payment_status.slice(1);
                    }
                }
            }
        }
    } catch (error) {
        console.error('❌ Update check failed:', error);
    }
}

function updateOrderDisplay(order) {
    // Update timeline with complete data
    updateTimeline(order.status, order.updated_at);

    // Update payment status
    const paymentBadge = document.getElementById('paymentStatusBadge');
    if (paymentBadge) {
        paymentBadge.className = `badge bg-${order.payment_status === 'paid' ? 'success' : (order.payment_status === 'failed' ? 'danger' : 'warning')}`;
        paymentBadge.textContent = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1);
    }

    // Update payment select
    const paymentSelect = document.getElementById('paymentStatusSelect');
    if (paymentSelect) {
        paymentSelect.value = order.payment_status;
    }

    // Update last updated time
    const now = new Date();
    document.getElementById('lastUpdated').textContent = 'Just now';
    document.getElementById('summaryUpdated').textContent = 'Just now';

    // Flash animation
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('update-flash');
        setTimeout(() => card.classList.remove('update-flash'), 1000);
    });
}

// ===========================================
// IMPROVED TIMELINE UPDATE FUNCTION
// ===========================================
function updateTimeline(status, updatedAt = null) {
    const statusOrder = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    const currentIndex = statusOrder.indexOf(status);
    const now = updatedAt ? new Date(updatedAt) : new Date();
    const formattedTime = formatDateTime(now);

    console.log('Updating timeline for status:', status, 'currentIndex:', currentIndex);

    // Get all timeline items
    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineItems.forEach((item, index) => {
        const marker = item.querySelector('.timeline-marker');
        const timeElement = item.querySelector('.text-muted span');

        if (marker) {
            // Reset all markers first
            marker.className = 'timeline-marker';

            if (status === 'cancelled') {
                // Special handling for cancelled status
                if (index === 0) {
                    // Only the first item (Order Placed) should be active for cancelled orders
                    marker.classList.add('cancelled');
                    if (timeElement) timeElement.textContent = 'Order was cancelled';
                } else {
                    marker.classList.add('inactive');
                    if (timeElement) timeElement.textContent = 'Cancelled';
                }
            } else {
                // Normal status flow
                if (index <= currentIndex) {
                    marker.classList.add('active');

                    // Update timestamp for completed and current steps
                    if (timeElement) {
                        if (index === currentIndex) {
                            // Current step - use the actual update time
                            timeElement.textContent = formattedTime;
                        } else if (index < currentIndex) {
                            // Previous steps - mark as completed
                            timeElement.textContent = 'Completed';
                        }
                    }
                } else {
                    marker.classList.add('inactive');

                    // Reset future steps to pending
                    if (timeElement) {
                        timeElement.textContent = 'Pending';
                    }
                }
            }
        }
    });

    // Update specific status timestamps
    updateStatusTimestamp(status, formattedTime);
}

// ===========================================
// UPDATE SPECIFIC STATUS TIMESTAMPS
// ===========================================
function updateStatusTimestamp(status, timestamp) {
    const statusMap = {
        'processing': 'processingTime',
        'shipped': 'shippedTime',
        'delivered': 'deliveredTime'
    };

    if (statusMap[status]) {
        const element = document.getElementById(statusMap[status]);
        if (element) {
            element.textContent = timestamp;
        }
    }

    // Special handling for cancelled status
    if (status === 'cancelled') {
        const processingTime = document.getElementById('processingTime');
        const shippedTime = document.getElementById('shippedTime');
        const deliveredTime = document.getElementById('deliveredTime');

        if (processingTime) processingTime.textContent = 'Cancelled';
        if (shippedTime) shippedTime.textContent = 'Cancelled';
        if (deliveredTime) deliveredTime.textContent = 'Cancelled';
    }
}

// ===========================================
// IMPROVED DATE/TIME FORMATTER
// ===========================================
function formatDateTime(date) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const month = months[date.getMonth()];
    const day = date.getDate();
    const year = date.getFullYear();
    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'

    return `${month} ${day}, ${year} ${hours}:${minutes} ${ampm}`;
}

function showUpdateAlert(message, duration = 5000) {
    const alert = document.getElementById('realtimeAlert');
    const alertMessage = document.getElementById('alertMessage');

    alert.classList.remove('d-none');
    alertMessage.innerHTML = message; // Use innerHTML for HTML content

    // Auto-hide after duration
    setTimeout(() => {
        alert.classList.add('d-none');
    }, duration);
}

// ===========================================
// HELPER FUNCTIONS
// ===========================================
// HTML escape helper to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ===========================================
// CUSTOM EMAIL SEND FUNCTION - SWEETALERT VERSION
// ===========================================
document.getElementById('emailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await sendCustomEmail();
});

async function sendCustomEmail() {
    const btn = document.getElementById('sendEmailBtn');
    const originalText = btn.innerHTML;
    const subject = document.getElementById('emailSubject').value.trim();
    const message = document.getElementById('emailMessage').value.trim();

    // Basic validation
    if (!subject) {
        await Swal.fire({
            title: 'Missing Subject',
            text: 'Please enter an email subject',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6ba932'
        });
        document.getElementById('emailSubject').focus();
        return;
    }

    if (!message) {
        await Swal.fire({
            title: 'Missing Message',
            text: 'Please enter an email message',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6ba932'
        });
        document.getElementById('emailMessage').focus();
        return;
    }

    // Show confirmation dialog
    const confirmResult = await Swal.fire({
        title: 'Send Email?',
        html: `
            <div class="text-start">
                <p><strong>To:</strong> {{ $order->user->email }}</p>
                <p><strong>Subject:</strong> ${escapeHtml(subject)}</p>
                <p><strong>Message Preview:</strong></p>
                <div class="bg-light p-2 rounded" style="max-height: 120px; overflow-y: auto; font-size: 0.9rem;">
                    ${escapeHtml(message.substring(0, 200))}${message.length > 200 ? '...' : ''}
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Send Email',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    });

    if (!confirmResult.isConfirmed) {
        return;
    }

    // Update button state
    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Sending...';

    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('subject', subject);
        formData.append('message', message);

        const response = await fetch(routes.sendEmail, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Success SweetAlert
            await Swal.fire({
                title: 'Email Sent!',
                html: `
                    <div class="text-start">
                        <p><i class="bi bi-envelope-check text-success"></i> <strong>Email sent successfully!</strong></p>
                        <p><strong>To:</strong> {{ $order->user->email }}</p>
                        <p><strong>Subject:</strong> ${escapeHtml(subject)}</p>
                        <p class="text-muted small">${new Date().toLocaleString()}</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6ba932',
                timer: 5000,
                timerProgressBar: true
            });

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
            modal.hide();

            // Reset form
            document.getElementById('emailMessage').value = '';

        } else {
            throw new Error(data.message || 'Failed to send email');
        }
    } catch (error) {
        console.error('Email sending error:', error);

        await Swal.fire({
            title: 'Error Sending Email',
            text: error.message || 'An unexpected error occurred while sending the email. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    } finally {
        // Restore button
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// ===========================================
// CUSTOM SMS SEND FUNCTION - SWEETALERT VERSION
// ===========================================
async function sendCustomSms() {
    const btn = document.getElementById('sendSmsBtn');
    const originalHtml = btn.innerHTML;
    const message = document.getElementById('smsMessage').value.trim();

    // Validate inputs
    if (!message) {
        await Swal.fire({
            title: 'Missing Message',
            text: 'Please enter an SMS message',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6ba932'
        });
        return;
    }

    if (message.length > 160) {
        await Swal.fire({
            title: 'Message Too Long',
            text: 'SMS message cannot exceed 160 characters',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6ba932'
        });
        return;
    }

    // Get recipient phone from the input field
    const recipientPhoneInput = document.getElementById('smsRecipient');
    const recipientPhone = recipientPhoneInput ? recipientPhoneInput.value : '{{ $order->shipping_phone }}';

    // Show confirmation dialog
    const confirmResult = await Swal.fire({
        title: 'Send SMS?',
        html: `
            <div class="text-start">
                <p><strong>To:</strong> ${escapeHtml(recipientPhone)}</p>
                <p><strong>Message:</strong></p>
                <p class="bg-light p-2 rounded" style="max-height: 100px; overflow-y: auto;">${escapeHtml(message)}</p>
                <p class="text-muted small">Characters: ${message.length}/160</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Send SMS',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#6ba932',
        cancelButtonColor: '#6c757d'
    });

    if (!confirmResult.isConfirmed) {
        return;
    }

    // Update button state before sending
    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Sending...';

    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('message', message);

        console.log('📱 Sending SMS with:', { phone: recipientPhone, message_length: message.length });

        const response = await fetch(routes.sendSms, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        let data;
        const contentType = response.headers.get('content-type');

        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            const text = await response.text();
            console.warn('⚠️ Non-JSON SMS response:', text);
            if (response.ok) {
                data = { success: true, message: 'SMS sent successfully' };
            } else {
                throw new Error(`Server error: ${response.status} - ${text}`);
            }
        }

        if (data.success || response.ok) {
            console.log('✅ SMS sent successfully!');

            // Get current timestamp
            const now = new Date();
            const timestamp = now.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Show SweetAlert2 success dialog
            await Swal.fire({
                title: 'SMS Sent!',
                html: `
                    <div class="text-start">
                        <p><i class="bi bi-telephone-check text-success"></i> <strong>SMS sent successfully!</strong></p>
                        <p><strong>To:</strong> ${escapeHtml(recipientPhone)}</p>
                        <p class="text-muted small">${timestamp}</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6ba932',
                timer: 5000,
                timerProgressBar: true
            });

            // Close modal AFTER success dialog confirmed
            const modalElement = document.getElementById('smsModal');
            if (modalElement) {
                try {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    } else {
                        // Force close if modal doesn't exist
                        modalElement.classList.remove('show');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    }
                } catch (e) {
                    console.warn('Could not close modal:', e);
                }
            }

            // Reset form completely
            const messageInput = document.getElementById('smsMessage');
            if (messageInput) {
                messageInput.value = '';
                // Trigger input event to update character counter
                messageInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

        } else {
            const errorMsg = data.message || data.error || 'Failed to send SMS. Please try again.';
            throw new Error(errorMsg);
        }
    } catch (error) {
        console.error('❌ SMS sending error:', error.message);

        // Show SweetAlert2 error dialog
        await Swal.fire({
            title: 'Error Sending SMS',
            text: error.message || 'An unexpected error occurred while sending the SMS. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    } finally {
        // Restore button to original state
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
}

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
// STATUS UPDATE FORM - SWEETALERT VERSION
// ===========================================
document.getElementById('statusUpdateForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const btn = document.getElementById('statusUpdateBtn');
    const originalText = btn.innerHTML;
    const statusSelect = document.getElementById('statusSelect');
    const newStatus = statusSelect.value;
    const newStatusText = statusSelect.options[statusSelect.selectedIndex].text;

    // Get current status for comparison
    const currentStatus = '{{ $order->status }}';

    // Don't proceed if status hasn't changed
    if (newStatus === currentStatus) {
        await Swal.fire({
            title: 'No Change',
            text: `Status is already set to ${newStatusText}. No changes made.`,
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6ba932'
        });
        return;
    }

    // Status labels for display
    const statusLabels = {
        'pending': '📋 Pending',
        'processing': '⚙️ Processing',
        'shipped': '🚚 Shipped',
        'delivered': '✅ Delivered',
        'cancelled': '❌ Cancelled'
    };

    // Confirmation dialog with SweetAlert
    const confirmResult = await Swal.fire({
        title: 'Update Order Status?',
        html: `
            <div class="text-start">
                <p><strong>Order #{{ $order->order_number }}</strong></p>
                <p>Current Status: <span class="badge bg-secondary">${statusLabels[currentStatus]}</span></p>
                <p>New Status: <span class="badge bg-primary">${statusLabels[newStatus]}</span></p>
                <p class="text-muted small mt-2">Customer will be notified via email and SMS.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update Status',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    });

    if (!confirmResult.isConfirmed) {
        return;
    }

    // Update button state
    btn.disabled = true;
    btn.innerHTML = '<span class="loading-spinner"></span> Updating...';

    try {
        // Create FormData from the form
        const formData = new FormData(form);

        const response = await fetch('{{ route("admin.orders.status", $order->id) }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `HTTP error! status: ${response.status}`);
        }

        if (data.success) {
            // Success handling with SweetAlert
            await Swal.fire({
                title: 'Status Updated!',
                html: `
                    <div class="text-start">
                        <p><i class="bi bi-check-circle text-success"></i> <strong>Order status updated successfully!</strong></p>
                        <p>Order #{{ $order->order_number }} status changed to:</p>
                        <p><span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">${statusLabels[newStatus]}</span></p>
                        <p class="text-muted small mt-2">Customer has been notified via email and SMS.</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6ba932',
                timer: 5000,
                timerProgressBar: true
            });

            // Update the timeline immediately
            updateTimeline(newStatus);

            // Update last updated time
            const now = new Date();
            document.getElementById('lastUpdated').textContent = 'Just now';
            document.getElementById('summaryUpdated').textContent = 'Just now';

            // Add visual feedback
            document.querySelectorAll('.timeline-item').forEach(item => {
                item.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
                setTimeout(() => item.style.backgroundColor = '', 1000);
            });

        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }

    } catch (error) {
        console.error('❌ Status update failed:', error);

        // Show user-friendly error message with SweetAlert
        let errorMessage = 'Failed to update status. ';

        if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
            errorMessage += 'Please check your internet connection and try again.';
        } else if (error.message.includes('500')) {
            errorMessage += 'Server error. Please try again later.';
        } else {
            errorMessage += error.message;
        }

        await Swal.fire({
            title: 'Update Failed',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });

        // Revert select to previous value on error
        statusSelect.value = currentStatus;

    } finally {
        // Always restore button state
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// ===========================================
// PAYMENT STATUS UPDATE FORM - SWEETALERT VERSION
// ===========================================
const paymentForm = document.getElementById('paymentUpdateForm');
const paymentStatusSelect = document.getElementById('paymentStatusSelect');
const paymentStatusBadge = document.getElementById('paymentStatusBadge');
const paymentUpdateBtn = document.getElementById('paymentUpdateBtn');

if (paymentForm) {
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get form values
        const newPaymentStatus = paymentStatusSelect.value;
        const currentPaymentStatus = '{{ $order->payment_status }}';

        // Prevent submission if status hasn't changed
        if (newPaymentStatus === currentPaymentStatus) {
            await Swal.fire({
                title: 'No Change',
                text: 'Payment status is already set to this value.',
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6ba932'
            });
            return;
        }

        // Get status labels for confirmation dialog
        const statusLabels = {
            'pending': '⏳ Pending',
            'paid': '✅ Paid',
            'failed': '❌ Failed',
            'refunded': '🔄 Refunded'
        };

        // Show SweetAlert confirmation dialog
        const confirmResult = await Swal.fire({
            title: 'Update Payment Status?',
            html: `
                <div style="text-align: left;">
                    <p><strong>Order #{{ $order->order_number }}</strong></p>
                    <p>Current Status: <span class="badge bg-info">${statusLabels[currentPaymentStatus]}</span></p>
                    <p>New Status: <span class="badge bg-warning">${statusLabels[newPaymentStatus]}</span></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        });

        // If user cancelled, do nothing
        if (!confirmResult.isConfirmed) {
            paymentStatusSelect.value = currentPaymentStatus;
            return;
        }

        // Disable form and button during submission
        paymentForm.style.opacity = '0.6';
        paymentForm.style.pointerEvents = 'none';

        const originalHtml = paymentUpdateBtn.innerHTML;
        paymentUpdateBtn.disabled = true;
        paymentUpdateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        paymentUpdateBtn.className = 'btn btn-warning w-100';

        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                              paymentForm.querySelector('input[name="_token"]')?.value;

            // Make AJAX request to update payment status
            const response = await fetch(
                '{{ route("admin.orders.payment", $order->id) }}',
                {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_status: newPaymentStatus
                    })
                }
            );

            const data = await response.json();

            if (data.success) {
                // Update UI elements
                if (paymentStatusBadge) {
                    paymentStatusBadge.className = 'badge';
                    const badgeClasses = {
                        'pending': 'bg-warning',
                        'paid': 'bg-success',
                        'failed': 'bg-danger',
                        'refunded': 'bg-info'
                    };
                    paymentStatusBadge.className = `badge ${badgeClasses[newPaymentStatus] || 'bg-secondary'}`;
                    paymentStatusBadge.textContent = statusLabels[newPaymentStatus];
                }

                // Update last modified timestamp
                const lastUpdatedElement = document.getElementById('lastUpdated');
                if (lastUpdatedElement) {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                    lastUpdatedElement.textContent = timeString;
                }

                // Show SweetAlert success dialog
                await Swal.fire({
                    title: 'Success!',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Payment Status Updated</strong></p>
                            <p>Order #{{ $order->order_number }} payment status changed to:</p>
                            <p><span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">${statusLabels[newPaymentStatus]}</span></p>
                            <p style="font-size: 0.9rem; color: #666; margin-top: 1rem;">Customer has been notified via email and SMS.</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6ba932',
                    timer: 5000,
                    timerProgressBar: true
                });

            } else {
                const errorMsg = data.message || 'Failed to update payment status. Please try again.';
                throw new Error(errorMsg);
            }

        } catch (error) {
            console.error('❌ Payment status update error:', error.message);

            // Reset form to previous value
            paymentStatusSelect.value = currentPaymentStatus;

            // Show SweetAlert error dialog
            await Swal.fire({
                title: 'Error Updating Payment Status',
                text: error.message || 'An unexpected error occurred while updating the payment status. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });

        } finally {
            // Restore button to original state
            paymentForm.style.opacity = '1';
            paymentForm.style.pointerEvents = 'auto';
            paymentUpdateBtn.disabled = false;
            paymentUpdateBtn.innerHTML = originalHtml;
            paymentUpdateBtn.className = 'btn btn-success w-100';
        }
    });
}

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
        checkForUpdates();
    }
});

// Initial check on page load and initialize timeline
window.addEventListener('load', function() {
    console.log('✅ Order #{{ $order->order_number }} page loaded successfully!');
    console.log('📍 Real-time timeline updates enabled.');
    console.log('⏱️ Auto-refresh interval: 30 seconds');
    console.log('💬 Notifications: Email & SMS');

    // Initialize timeline with current status
    updateTimeline('{{ $order->status }}');
});
</script>
@endsection
