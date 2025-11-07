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
    }
    .timeline-marker.active {
        background: #28a745;
    }
    .timeline-marker.inactive {
        background: #6c757d;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        body {
            padding: 20px;
        }
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
            flex-direction: column;
            gap: 10px;
        }
        .order-header .btn {
            width: 100% !important;
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

        .table-responsive {
            font-size: 0.85rem;
        }
        .table td, .table th {
            padding: 0.5rem !important;
        }

        /* Stack product info vertically on mobile */
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

        /* Mobile card info tables */
        .info-table-mobile td {
            padding: 8px 5px !important;
            font-size: 0.9rem;
        }

        /* Sticky action buttons on mobile */
        .mobile-sticky-actions {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }

        @media (max-width: 991px) {
            .mobile-sticky-actions {
                display: block;
            }
            body {
                padding-bottom: 70px;
            }
        }
    }

    @media (max-width: 576px) {
        .card-header h5 {
            font-size: 1rem;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }
        .badge {
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 order-header">
    <div>
        <h2 class="mb-1"><i class="bi bi-receipt"></i> Order #{{ $order->order_number }}</h2>
        <small class="text-muted">{{ $order->created_at->format('F d, Y h:i A') }}</small>
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

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Order Status Timeline -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Order Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
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
                                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                        {{ $order->updated_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending
                                    @endif
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
                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        {{ $order->updated_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending
                                    @endif
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
                                    @if($order->status == 'delivered')
                                        {{ $order->updated_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending
                                    @endif
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
                        <tbody>
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
                                <td class="text-end"><strong>₱{{ number_format($order->subtotal, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Shipping Fee:</strong></td>
                                <td class="text-end"><strong>₱{{ number_format($order->shipping_fee, 2) }}</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                <td class="text-end"><h5 class="mb-0 text-primary">₱{{ number_format($order->total, 2) }}</h5></td>
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
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0 info-table-mobile">
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
                    <div class="card-body">
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
                <p class="mb-0 text-muted">{{ $order->notes }}</p>
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
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <select name="status" class="form-select form-select-lg">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>📋 Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                        <small class="text-muted">Customer will be notified</small>
                    </div>

                    @if($order->status == 'processing' || $order->status == 'pending')
                    <div class="mb-3">
                        <label class="form-label">Tracking # (Optional)</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="Enter tracking number">
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
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
                            <span class="badge bg-info">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Update Payment</label>
                        <select name="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
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
                <table class="table table-sm table-borderless info-table-mobile">
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
                        <td class="text-end">{{ $order->updated_at->diffForHumans() }}</td>
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
<div class="mobile-sticky-actions no-print">
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
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">
                    <i class="bi bi-envelope me-2"></i>Send Email Notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Send an email notification to <strong>{{ $order->user->email }}</strong>?</p>
                <div class="alert alert-info">
                    <small>
                        <i class="bi bi-info-circle"></i>
                        This will send an order status update email to the customer.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.orders.send-email', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Send Email
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smsModalLabel">
                    <i class="bi bi-phone me-2"></i>Send SMS Notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Send an SMS notification to <strong>{{ $order->user->phone ?? 'N/A' }}</strong>?</p>

                @if(empty($order->user->phone))
                    <div class="alert alert-warning">
                        <small>
                            <i class="bi bi-exclamation-triangle"></i>
                            No phone number available for this customer.
                        </small>
                    </div>
                @else
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            This will send an order status update SMS based on the current order status.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Custom Message (Optional)</label>
                        <textarea class="form-control" name="custom_message" rows="3"
                                  placeholder="Leave empty to use default message..."
                                  maxlength="160"></textarea>
                        <div class="form-text">
                            <span id="charCounter">0/160 characters</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                @if(!empty($order->user->phone))
                    <form action="{{ route('admin.orders.send-sms', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i> Send SMS
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Character counter for SMS modal
    document.addEventListener('DOMContentLoaded', function() {
        const smsTextarea = document.querySelector('#smsModal textarea[name="custom_message"]');
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

            // Trigger initial count
            smsTextarea.dispatchEvent(new Event('input'));
        }

        // Auto-hide sticky actions on scroll up
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

        // Confirmation before status change
        document.querySelectorAll('form[action*="status"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const statusSelect = this.querySelector('select[name="status"]');
                const newStatus = statusSelect.options[statusSelect.selectedIndex].text;

                if (!confirm(`Change order status to ${newStatus}? Customer will be notified via email and SMS.`)) {
                    e.preventDefault();
                }
            });
        });

        // Confirmation before payment status change
        document.querySelectorAll('form[action*="payment"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const statusSelect = this.querySelector('select[name="payment_status"]');
                const newStatus = statusSelect.options[statusSelect.selectedIndex].text;

                if (!confirm(`Change payment status to ${newStatus}?`)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection
