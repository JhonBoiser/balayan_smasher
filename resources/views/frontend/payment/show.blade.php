@extends('layouts.app')

@section('title', 'Payment - Balayan Smashers Hub')

@section('content')
<style>
    .payment-section {
        padding: 50px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 200px);
    }

    .payment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .payment-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .payment-header {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 40px 24px;
        text-align: center;
    }

    .payment-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .order-number {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 8px;
    }

    .payment-body {
        padding: 40px;
    }

    .payment-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .order-details {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        color: #6c757d;
        font-weight: 500;
    }

    .detail-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .total-amount {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin-top: 24px;
    }

    .total-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .total-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 8px 0 0;
    }

    .payment-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .payment-method-btn {
        padding: 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: #2c3e50;
    }

    .payment-method-btn:hover {
        border-color: var(--primary-green);
        background: rgba(107, 169, 50, 0.05);
    }

    .payment-method-btn.active {
        border-color: var(--primary-green);
        background: rgba(107, 169, 50, 0.15);
        color: var(--primary-green);
    }

    .payment-method-icon {
        font-size: 1.5rem;
    }

    .payment-form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .payment-form-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .payment-form-input {
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .payment-form-input:focus {
        outline: none;
        border-color: var(--primary-green);
        background: white;
        box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1);
    }

    #card-element {
        padding: 12px 16px !important;
        border: 2px solid #e9ecef !important;
        border-radius: 8px !important;
        background: #f8f9fa !important;
        font-size: 0.95rem !important;
    }

    #card-element.StripeElement--focus {
        border-color: var(--primary-green) !important;
        background: white !important;
        box-shadow: 0 0 0 3px rgba(107, 169, 50, 0.1) !important;
    }

    .payment-btn {
        padding: 14px 24px;
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .payment-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 169, 50, 0.3);
    }

    .payment-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #f5c6cb;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #c3e6cb;
        display: none;
    }

    .success-message.show {
        display: block;
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 12px 16px;
        border-radius: 4px;
        color: #1565c0;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        gap: 12px;
    }

    @media (max-width: 768px) {
        .payment-body {
            padding: 24px;
        }

        .payment-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .payment-header h1 {
            font-size: 1.5rem;
        }

        .total-value {
            font-size: 1.5rem;
        }
    }
</style>

<section class="payment-section">
    <div class="payment-container">
        <a href="{{ route('orders.show', $order->id) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Order
        </a>

        <div class="payment-card">
            <div class="payment-header">
                <h1>Complete Payment</h1>
                <div class="order-number">Order #{{ $order->order_number }}</div>
            </div>

            <div class="payment-body">
                <div class="payment-layout">
                    <!-- Order Details -->
                    <div>
                        <h3 style="margin-top: 0; color: #2c3e50; font-size: 1.2rem;">Order Details</h3>
                        <div class="order-details">
                            <div class="detail-row">
                                <span class="detail-label">Order Number:</span>
                                <span class="detail-value">{{ $order->order_number }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Items:</span>
                                <span class="detail-value">{{ $order->items->count() }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Subtotal:</span>
                                <span class="detail-value">₱{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Shipping:</span>
                                <span class="detail-value">₱{{ number_format($order->shipping_fee, 2) }}</span>
                            </div>

                            <div class="total-amount">
                                <div class="total-label">Total Amount Due</div>
                                <div class="total-value">₱{{ number_format($order->total, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div>
                        <h3 style="margin-top: 0; color: #2c3e50; font-size: 1.2rem;">Payment Method</h3>

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            Securely powered by PayMongo. Your payment is encrypted and protected.
                        </div>

                        <div class="error-message" id="errorMessage"></div>
                        <div class="success-message" id="successMessage"></div>

                        <form id="paymentForm" class="payment-form">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" id="intentId" name="intent_id">
                            <input type="hidden" id="paymentMethodId" name="payment_method_id">

                            <!-- Payment Methods Selection -->
                            <div class="payment-methods">
                                <button type="button" class="payment-method-btn active" data-method="card" onclick="selectPaymentMethod('card', event)">
                                    <i class="fas fa-credit-card payment-method-icon"></i>
                                    <span>Credit/Debit Card</span>
                                </button>
                                <button type="button" class="payment-method-btn" data-method="gcash" onclick="selectPaymentMethod('gcash', event)">
                                    <i class="fas fa-mobile-alt payment-method-icon"></i>
                                    <span>GCash</span>
                                </button>
                                <button type="button" class="payment-method-btn" data-method="grab_pay" onclick="selectPaymentMethod('grab_pay', event)">
                                    <i class="fas fa-wallet payment-method-icon"></i>
                                    <span>Grab Pay</span>
                                </button>
                            </div>

                            <!-- Card Details (Only for Card) -->
                            <div id="cardSection" class="payment-form-group">
                                <label class="payment-form-label">Card Details</label>
                                <div id="card-element" class="payment-form-input"></div>
                            </div>

                            <!-- Non-Card Methods Info -->
                            <div id="nonCardInfo" style="display: none; background: #f8f9fa; padding: 16px; border-radius: 8px; color: #6c757d; font-size: 0.9rem;">
                                <p style="margin: 0;">You will be redirected to complete your payment with <span id="methodName"></span>.</p>
                            </div>

                            <button type="submit" class="payment-btn" id="paymentBtn">
                                <i class="fas fa-lock"></i>
                                <span id="paymentBtnText">Pay ₱{{ number_format($order->total, 2) }}</span>
                            </button>
                        </form>

                        <p style="margin-top: 16px; text-align: center; font-size: 0.85rem; color: #6c757d;">
                            <i class="fas fa-shield-alt"></i>
                            Your payment information is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PayMongo JS SDK -->
<script src="https://js.paymongo.com/v1/paymongo.js"></script>

<script>
    const publicKey = '{{ $publicKey }}';
    const orderId = {{ $order->id }};
    const orderTotal = {{ $order->total }};
    let selectedPaymentMethod = 'card';
    let paymongoInstance;
    let cardElement;

    // Initialize PayMongo
    function initializePayMongo() {
        if (!publicKey) {
            showError('PayMongo is not properly configured.');
            return;
        }

        paymongoInstance = new PayMongo(publicKey);
        cardElement = paymongoInstance.elements.create('card');
        cardElement.mount('#card-element');
    }

    // Select payment method
    function selectPaymentMethod(method, event) {
        event.preventDefault();
        selectedPaymentMethod = method;

        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.closest('.payment-method-btn').classList.add('active');

        const cardSection = document.getElementById('cardSection');
        const nonCardInfo = document.getElementById('nonCardInfo');

        if (method === 'card') {
            cardSection.style.display = 'flex';
            nonCardInfo.style.display = 'none';
        } else {
            cardSection.style.display = 'none';
            nonCardInfo.style.display = 'block';
            document.getElementById('methodName').textContent =
                method === 'gcash' ? 'GCash' : 'Grab Pay';
        }
    }

    // Handle form submission
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('paymentBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        try {
            // Step 1: Create payment intent
            const intentResponse = await fetch('{{ route("payment.create-intent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    order_id: orderId,
                    payment_method: selectedPaymentMethod
                })
            });

            const intentData = await intentResponse.json();
            if (!intentData.success) {
                showError(intentData.error || 'Failed to create payment intent');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock"></i> <span id="paymentBtnText">Pay ₱' + orderTotal.toFixed(2) + '</span>';
                return;
            }

            const clientKey = intentData.data.client_key;
            const intentId = intentData.data.intent_id;
            document.getElementById('intentId').value = intentId;

            // Step 2: Create payment method
            let paymentMethod;

            if (selectedPaymentMethod === 'card') {
                paymentMethod = await paymongoInstance.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        email: '{{ $order->shipping_email }}',
                        name: '{{ $order->shipping_name }}'
                    }
                });
            } else {
                paymentMethod = await paymongoInstance.createPaymentMethod({
                    type: selectedPaymentMethod,
                    redirect: {
                        success: '{{ route("payment.return", ["orderId" => $order->id]) }}',
                        failed: '{{ route("payment.failure", ["orderId" => $order->id]) }}'
                    },
                    billing_details: {
                        email: '{{ $order->shipping_email }}',
                        name: '{{ $order->shipping_name }}'
                    }
                });
            }

            if (paymentMethod.error) {
                showError(paymentMethod.error.message || 'Failed to create payment method');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock"></i> <span id="paymentBtnText">Pay ₱' + orderTotal.toFixed(2) + '</span>';
                return;
            }

            document.getElementById('paymentMethodId').value = paymentMethod.data.id;

            // Step 3: Process payment
            const processResponse = await fetch('{{ route("payment.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    order_id: orderId,
                    intent_id: intentId,
                    payment_method_id: paymentMethod.data.id
                })
            });

            const processData = await processResponse.json();
            if (!processData.success) {
                showError(processData.error || 'Payment processing failed');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock"></i> <span id="paymentBtnText">Pay ₱' + orderTotal.toFixed(2) + '</span>';
                return;
            }

            showSuccess('Payment processed successfully!');
            setTimeout(() => {
                window.location.href = processData.data.redirect;
            }, 1500);

        } catch (error) {
            console.error('Payment error:', error);
            showError(error.message || 'An error occurred during payment processing');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> <span id="paymentBtnText">Pay ₱' + orderTotal.toFixed(2) + '</span>';
        }
    });

    // Show/hide error message
    function showError(message) {
        const errorDiv = document.getElementById('errorMessage');
        errorDiv.textContent = message;
        errorDiv.classList.add('show');
    }

    function showSuccess(message) {
        const successDiv = document.getElementById('successMessage');
        successDiv.textContent = message;
        successDiv.classList.add('show');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializePayMongo();
    });
</script>
@endsection
