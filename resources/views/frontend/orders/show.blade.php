@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Balayan Smashers Hub')

@section('content')
<style>
    .order-details-container {
        padding: 40px 0;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .order-header {
         background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .order-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 25px;
        overflow: hidden;
    }

    .order-card-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 20px 25px;
        border-bottom: none;
    }

    .order-card-header h5 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-card-body {
        padding: 25px;
    }

    .order-item {
        display: flex;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #eef2f7;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        background-color: #fafbfc;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        margin-right: 20px;
        flex-shrink: 0;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e9ecef;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    .item-price {
        color: var(--primary-green);
        font-weight: 600;
        font-size: 1rem;
    }

    .item-quantity {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .item-subtotal {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
        text-align: right;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #6c757d;
        font-weight: 500;
    }

    .summary-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .total-amount {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-green);
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .shipping-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
    }

    .shipping-info p {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shipping-info strong {
        color: #2c3e50;
        min-width: 100px;
    }

    .back-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .no-image-placeholder {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        text-align: center;
        padding: 10px;
    }

    @media (max-width: 768px) {
        .order-details-container {
            padding: 20px 0;
        }

        .order-header {
            padding: 20px;
            margin-bottom: 20px;
        }

        .order-card-body {
            padding: 20px;
        }

        .order-item {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            padding: 15px 0;
        }

        .item-image {
            margin-right: 0;
            margin-bottom: 15px;
            width: 100%;
            height: 120px;
        }

        .item-subtotal {
            text-align: left;
            margin-top: 10px;
            width: 100%;
        }

        .summary-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .summary-value {
            align-self: flex-end;
        }
    }
</style>

<div class="order-details-container">
    <div class="container">
        <!-- Order Header -->
        <div class="order-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 mb-2">Order #{{ $order->order_number }}</h1>
                    <p class="mb-0 opacity-75">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <a href="{{ route('orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Orders
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="order-card">
                    <div class="order-card-header">
                        <h5><i class="fas fa-shopping-bag"></i> Order Items ({{ $order->items->count() }})</h5>
                    </div>
                    <div class="order-card-body">
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    @php
                                        $imageUrl = 'https://via.placeholder.com/300x300?text=No+Image';
                                        $imageAlt = $item->product_name ?? 'Product Image';

                                        // Multiple fallback methods for product image
                                        if ($item->product && $item->product->primaryImage) {
                                            $imagePath = $item->product->primaryImage->image_path;
                                            if (strpos($imagePath, 'http') === 0) {
                                                $imageUrl = $imagePath;
                                            } else {
                                                $imageUrl = asset('storage/' . $imagePath);
                                            }
                                        } elseif ($item->product && $item->product->images && $item->product->images->count() > 0) {
                                            $firstImage = $item->product->images->first();
                                            $imagePath = $firstImage->image_path;
                                            if (strpos($imagePath, 'http') === 0) {
                                                $imageUrl = $imagePath;
                                            } else {
                                                $imageUrl = asset('storage/' . $imagePath);
                                            }
                                        } elseif ($item->product && $item->product->image_url) {
                                            $imageUrl = $item->product->image_url;
                                        }
                                    @endphp

                                    <img src="{{ $imageUrl }}"
                                         alt="{{ $imageAlt }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/300x300?text=Image+Not+Found'; this.classList.add('img-error');">
                                </div>
                                <div class="item-details">
                                    <div class="item-name">{{ $item->product_name }}</div>
                                    <div class="item-price">₱{{ number_format($item->price, 2) }} each</div>
                                    <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                                    @if(!$item->product)
                                        <div class="text-warning small mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> Product no longer available
                                        </div>
                                    @endif
                                </div>
                                <div class="item-subtotal">
                                    ₱{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart"></i>
                                <h4>No items found in this order</h4>
                                <p>There are no items associated with this order.</p>
                            </div>
                        @endif
                        <!-- Show cancel button if order can be cancelled -->
@if($order->canBeCancelled())
    <a href="{{ route('orders.cancel.form', $order->id) }}" class="btn btn-danger">
        <i class="fas fa-times-circle"></i> Cancel Order
    </a>
@endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <!-- Order Status -->
                <div class="order-card mb-4">
                    <div class="order-card-header">
                        <h5><i class="fas fa-info-circle"></i> Order Summary</h5>
                    </div>
                    <div class="order-card-body">
                        <div class="summary-item">
                            <span class="summary-label">Status:</span>
                            <span class="status-badge bg-{{ $order->getStatusBadgeClass() }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Payment Method:</span>
                            <span class="summary-value text-capitalize">
                                {{ str_replace('_', ' ', $order->payment_method) }}
                            </span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Payment Status:</span>
                            <span class="summary-value text-capitalize">
                                {{ str_replace('_', ' ', $order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="order-card mb-4">
                    <div class="order-card-header">
                        <h5><i class="fas fa-receipt"></i> Price Breakdown</h5>
                    </div>
                    <div class="order-card-body">
                        <div class="summary-item">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Shipping Fee:</span>
                            <span class="summary-value">₱{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        <hr>
                        <div class="summary-item">
                            <span class="summary-label total-amount">Total Amount:</span>
                            <span class="summary-value total-amount">₱{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="order-card">
                    <div class="order-card-header">
                        <h5><i class="fas fa-truck"></i> Shipping Information</h5>
                    </div>
                    <div class="order-card-body">
                        <div class="shipping-info">
                            <p>
                                <strong>Name:</strong>
                                <span>{{ $order->shipping_name }}</span>
                            </p>
                            <p>
                                <strong>Phone:</strong>
                                <span>{{ $order->shipping_phone }}</span>
                            </p>
                            <p>
                                <strong>Address:</strong>
                                <span>{{ $order->shipping_address }}</span>
                            </p>
                            <p>
                                <strong>City/Province:</strong>
                                <span>{{ $order->shipping_city }}, {{ $order->shipping_province }}</span>
                            </p>
                            <p>
                                <strong>ZIP Code:</strong>
                                <span>{{ $order->shipping_zipcode }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const orderItems = document.querySelectorAll('.order-item');
    orderItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.5s ease';

        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 100);
    });

    // Handle image errors
    const images = document.querySelectorAll('.item-image img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'https://via.placeholder.com/300x300?text=Image+Not+Found';
            this.alt = 'Image not available';
            this.style.objectFit = 'contain';
            this.style.padding = '10px';
        });
    });

    // Add hover effects to cards
    const cards = document.querySelectorAll('.order-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection
