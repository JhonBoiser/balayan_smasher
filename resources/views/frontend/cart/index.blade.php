@extends('layouts.app')

@section('title', 'Shopping Cart - Balayan Smashers Hub')

@section('content')
<style>
    /* Cart Section */
    .cart-section {
        padding: 0 0 50px;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: var(--primary-green);
    }

    /* Cart Layout */
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    /* Cart Items */
    .cart-items {
        background: white;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
    }

    .cart-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 12px 12px 0 0;
    }

    .cart-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .cart-body {
        padding: 24px;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 60px;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .item-image {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .item-category {
        color: var(--primary-green);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-green);
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        border: 2px solid #e8ecef;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        font-weight: 600;
        color: #6c757d;
    }

    .quantity-btn:hover {
        border-color: var(--primary-green);
        color: var(--primary-green);
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        border-color: #e8ecef;
        color: #6c757d;
    }

    .quantity-input {
        width: 50px;
        height: 32px;
        border: 2px solid #e8ecef;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        background: #fafbfc;
        transition: all 0.3s ease;
    }

    .quantity-input:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1);
        background: white;
    }

    .item-subtotal {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .item-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .btn-remove {
        background: #f44336;
        color: white;
        border: none;
        border-radius: 6px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        background: #d32f2f;
        transform: translateY(-1px);
    }

    /* Order Summary */
    .order-summary {
        background: white;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .summary-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 12px 12px 0 0;
    }

    .summary-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .summary-body {
        padding: 24px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .summary-label {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .summary-value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .summary-divider {
        border-top: 2px solid #f0f0f0;
        margin: 20px 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .total-label {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .total-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary-green);
    }

    .btn-checkout {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 12px;
        text-decoration: none;
        text-align: center;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-continue-shopping {
        width: 100%;
        padding: 14px;
        background: transparent;
        color: var(--primary-green);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        text-align: center;
    }

    .btn-continue-shopping:hover {
        background: var(--primary-green);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
    }

    .empty-cart-icon {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 24px;
    }

    .empty-cart h3 {
        color: #6c757d;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .empty-cart p {
        color: #adb5bd;
        margin-bottom: 30px;
        font-size: 1rem;
    }

    .btn-shopping {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-shopping:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Mobile Delete Button */
    .mobile-delete-btn {
        display: none;
        position: absolute;
        top: 20px;
        right: 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .cart-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .order-summary {
            position: static;
        }

        .cart-item {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 24px 0;
            position: relative;
        }

        .item-info {
            grid-column: 1;
            padding-right: 50px;
        }

        .item-price {
            grid-column: 1;
            text-align: left;
            font-size: 1.2rem;
        }

        .quantity-control {
            grid-column: 1;
            justify-content: flex-start;
        }

        .item-subtotal {
            grid-column: 1;
            text-align: left;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-green);
        }

        /* Hide desktop delete button on mobile */
        .item-actions {
            display: none;
        }

        /* Show mobile delete button */
        .mobile-delete-btn {
            display: block;
        }

        .item-details h4 {
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .item-category {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 768px) {
        .cart-section {
            padding: 0 0 30px;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .cart-container {
            padding: 0 16px;
        }

        .cart-body,
        .summary-body,
        .empty-cart {
            padding: 20px;
        }

        .item-info {
            flex-direction: row;
            align-items: center;
            text-align: left;
        }

        .item-image {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
        }

        .mobile-delete-btn {
            top: 24px;
            right: 0;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.4rem;
        }

        .empty-cart {
            padding: 60px 16px;
        }

        .empty-cart-icon {
            font-size: 4rem;
        }

        .summary-body {
            padding: 20px;
        }

        .item-info {
            flex-direction: column;
            align-items: flex-start;
            padding-right: 40px;
        }

        .item-image {
            width: 100%;
            height: 120px;
            margin-bottom: 12px;
        }

        .mobile-delete-btn {
            top: 24px;
            right: 0;
        }

        .quantity-control {
            width: 100%;
        }

        .quantity-input {
            width: 60px;
        }
    }
</style>

<!-- Cart Section -->
<section class="cart-section">
    <div class="cart-container">
        <h1 class="page-title">
            <i class="fas fa-shopping-cart"></i>
            Shopping Cart
        </h1>

        @if($cartItems->count() > 0)
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items">
                <div class="cart-header">
                    <h3>Cart Items</h3>
                </div>
                <div class="cart-body">
                    @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div class="item-info">
                            <div class="item-image">
                                @if($item->product->primaryImage)
                                    <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x300?text=No+Image" alt="{{ $item->product->name }}">
                                @endif
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->name }}</h4>
                                <div class="item-category">{{ $item->product->category->name }}</div>
                            </div>
                        </div>

                        <div class="item-price">
                            ₱{{ number_format($item->product->getCurrentPrice(), 2) }}
                        </div>

                        <div class="quantity-control">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="quantity-btn minus-btn" onclick="decreaseQuantity({{ $item->id }})"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" class="quantity-input"
                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                       id="quantity-{{ $item->id }}" readonly>
                                <button type="button" class="quantity-btn plus-btn" onclick="increaseQuantity({{ $item->id }}, {{ $item->product->stock }})"
                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>

                        <div class="item-subtotal">
                            ₱{{ number_format($item->getSubtotal(), 2) }}
                        </div>

                        <!-- Desktop Delete Button -->
                        <div class="item-actions">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove" title="Remove item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Mobile Delete Button -->
                        <div class="mobile-delete-btn">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove" title="Remove item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-header">
                    <h3>Order Summary</h3>
                </div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping:</span>
                        <span class="summary-value">₱{{ number_format($shippingFee, 2) }}</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total">
                        <span class="total-label">Total:</span>
                        <span class="total-value">₱{{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn-checkout">
                        <i class="fas fa-credit-card"></i>
                        Proceed to Checkout
                    </a>

                    <a href="{{ route('products.index') }}" class="btn-continue-shopping">
                        <i class="fas fa-arrow-left"></i>
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>Your cart is empty</h3>
            <p>Start shopping to add items to your cart!</p>
            <a href="{{ route('products.index') }}" class="btn-shopping">
                <i class="fas fa-store"></i>
                Browse Products
            </a>
        </div>
        @endif
    </div>
</section>

<script>
// Quantity control functions
function increaseQuantity(itemId, maxStock) {
    const input = document.getElementById(`quantity-${itemId}`);
    const currentValue = parseInt(input.value);
    const plusBtn = input.nextElementSibling;

    if (currentValue < maxStock) {
        input.value = currentValue + 1;
        updateQuantity(itemId, input.value);

        // Enable minus button if it was disabled
        const minusBtn = input.previousElementSibling;
        minusBtn.disabled = false;

        // Disable plus button if reached max
        if (currentValue + 1 >= maxStock) {
            plusBtn.disabled = true;
        }
    }
}

function decreaseQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const currentValue = parseInt(input.value);
    const minusBtn = input.previousElementSibling;

    if (currentValue > 1) {
        input.value = currentValue - 1;
        updateQuantity(itemId, input.value);

        // Enable plus button if it was disabled
        const plusBtn = input.nextElementSibling;
        plusBtn.disabled = false;

        // Disable minus button if reached min
        if (currentValue - 1 <= 1) {
            minusBtn.disabled = true;
        }
    }
}

function updateQuantity(itemId, quantity) {
    const form = document.querySelector(`#quantity-${itemId}`).closest('form');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Reload the page to update totals
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
    });
}

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cart items
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add hover effects to quantity buttons
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    quantityButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            if (!this.disabled) {
                this.style.borderColor = 'var(--primary-green)';
                this.style.color = 'var(--primary-green)';
            }
        });
        button.addEventListener('mouseleave', function() {
            if (!this.disabled) {
                this.style.borderColor = '#e8ecef';
                this.style.color = '#6c757d';
            }
        });
    });
});
</script>
@endsection
