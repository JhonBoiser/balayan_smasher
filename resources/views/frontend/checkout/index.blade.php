@extends('layouts.app')

@section('title', 'Checkout - Balayan Smashers Hub')

@section('content')
<style>
    /* Checkout Section */
    .checkout-section {
        padding: 0 0 50px;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    /* Checkout Layout */
    .checkout-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    /* Checkout Cards */
    .checkout-card {
        background: white;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e8ecef;
        margin-bottom: 24px;
    }

    .checkout-card:last-child {
        margin-bottom: 0;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 12px 12px 0 0;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .card-body {
        padding: 24px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .form-label.required::after {
        content: ' *';
        color: #f44336;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8ecef;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1);
        background: white;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 2px solid #e8ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .payment-option:hover {
        border-color: var(--primary-green);
        background: rgba(107, 169, 50, 0.05);
    }

    .payment-option.selected {
        border-color: var(--primary-green);
        background: rgba(107, 169, 50, 0.1);
    }

    .payment-input {
        margin: 0;
    }

    .payment-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #2c3e50;
        cursor: pointer;
        margin: 0;
    }

    .payment-icon {
        font-size: 1.2rem;
        color: var(--primary-green);
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

    .summary-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 16px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .item-name {
        flex: 1;
        font-size: 0.9rem;
        color: #2c3e50;
        line-height: 1.4;
    }

    .item-quantity {
        color: #6c757d;
        font-size: 0.8rem;
        margin-left: 8px;
    }

    .item-price {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
        text-align: right;
    }

    .summary-divider {
        border-top: 2px solid #f0f0f0;
        margin: 20px 0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
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

    .btn-place-order {
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
    }

    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 169, 50, 0.3);
    }

    .btn-place-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .checkout-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .order-summary {
            position: static;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    @media (max-width: 768px) {
        .checkout-section {
            padding: 0 0 30px;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .checkout-container {
            padding: 0 16px;
        }

        .card-body {
            padding: 20px;
        }

        .payment-option {
            padding: 12px;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.4rem;
        }

        .card-header {
            padding: 16px 20px;
        }

        .card-header h3 {
            font-size: 1.1rem;
        }

        .form-control {
            padding: 10px 14px;
        }
    }
</style>

<!-- Checkout Section -->
<section class="checkout-section">
    <div class="checkout-container">
        <h1 class="page-title">Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-layout">
                <!-- Left Column - Forms -->
                <div class="checkout-forms">
                    <!-- Shipping Information -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <h3>Shipping Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">Full Name</label>
                                    <input type="text" name="shipping_name" class="form-control" required value="{{ auth()->user()->name }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Email</label>
                                    <input type="email" name="shipping_email" class="form-control" required value="{{ auth()->user()->email }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Phone</label>
                                <input type="text" name="shipping_phone" class="form-control" required value="{{ auth()->user()->phone }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Address</label>
                                <textarea name="shipping_address" class="form-control" rows="2" required>{{ auth()->user()->address }}</textarea>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">City</label>
                                    <input type="text" name="shipping_city" class="form-control" required value="{{ auth()->user()->city }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Province</label>
                                    <input type="text" name="shipping_province" class="form-control" required value="{{ auth()->user()->province }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Zip Code</label>
                                    <input type="text" name="shipping_zipcode" class="form-control" required value="{{ auth()->user()->zipcode }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <h3>Payment Method</h3>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <div class="payment-option selected" onclick="selectPayment('cod')">
                                    <input class="payment-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                                    <label class="payment-label" for="cod">
                                        <i class="fas fa-money-bill-wave payment-icon"></i>
                                        Cash on Delivery
                                    </label>
                                </div>

                                <div class="payment-option" onclick="selectPayment('gcash')">
                                    <input class="payment-input" type="radio" name="payment_method" value="gcash" id="gcash">
                                    <label class="payment-label" for="gcash">
                                        <i class="fas fa-mobile-alt payment-icon"></i>
                                        GCash
                                    </label>
                                </div>

                                <div class="payment-option" onclick="selectPayment('bank_transfer')">
                                    <input class="payment-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                                    <label class="payment-label" for="bank">
                                        <i class="fas fa-university payment-icon"></i>
                                        Bank Transfer
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 24px;">
                                <label class="form-label">Order Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions for your order..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="order-summary">
                    <div class="card-header">
                        <h3>Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="summary-items">
                            @foreach($cartItems as $item)
                            <div class="summary-item">
                                <div class="item-name">
                                    {{ $item->product->name }}
                                    <span class="item-quantity">x{{ $item->quantity }}</span>
                                </div>
                                <div class="item-price">
                                    ₱{{ number_format($item->getSubtotal(), 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="summary-divider"></div>

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

                        <button type="submit" class="btn-place-order" id="placeOrderBtn">
                            <i class="fas fa-check-circle"></i>
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
// Payment method selection
function selectPayment(method) {
    // Update radio button
    document.querySelector(`input[value="${method}"]`).checked = true;

    // Update visual selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.classList.remove('selected');
    });
    document.querySelector(`input[value="${method}"]`).closest('.payment-option').classList.add('selected');
}

// Form submission handling
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('placeOrderBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
});

// Add form validation feedback
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value) {
            this.style.borderColor = '#f44336';
        } else {
            this.style.borderColor = '#e8ecef';
        }
    });

    input.addEventListener('input', function() {
        if (this.value) {
            this.style.borderColor = '#e8ecef';
        }
    });
});

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.checkout-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endsection
