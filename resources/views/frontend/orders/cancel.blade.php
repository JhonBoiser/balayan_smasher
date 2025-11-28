@extends('layouts.app')

@section('title', 'Cancel Order - Balayan Smashers Hub')

@section('content')
<style>
    .cancel-order-container {
        padding: 40px 0;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .cancel-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        border: none;
        max-width: 600px;
        margin: 0 auto;
    }

    .cancel-card-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 25px;
        border-radius: 15px 15px 0 0;
        text-align: center;
    }

    .cancel-card-body {
        padding: 30px;
    }

    .order-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .reason-option {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .reason-option:hover {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .reason-option.selected {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .reason-option input[type="radio"] {
        margin-right: 10px;
    }

    .custom-reason {
        display: none;
        margin-top: 15px;
    }

    .btn-cancel {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-back {
        background: transparent;
        color: #6c757d;
        border: 2px solid #6c757d;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #6c757d;
        color: white;
    }
</style>

<div class="cancel-order-container">
    <div class="container">
        <div class="cancel-card">
            <div class="cancel-card-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Cancel Order</h2>
                <p class="mb-0">Order #{{ $order->order_number }}</p>
            </div>

            <div class="cancel-card-body">
                <!-- Order Information -->
                <div class="order-info">
                    <h5>Order Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Order Date:</strong> {{ $order->created_at->format('M j, Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Total Amount:</strong> ₱{{ number_format($order->total, 2) }}
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Cancellation Form -->
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <h5>Why are you cancelling this order?</h5>
                        <p class="text-muted">Please select the main reason for cancellation:</p>

                        <div class="reason-options">
                            <div class="reason-option" onclick="selectReason('changed_mind', this)">
                                <input type="radio" name="cancellation_reason" value="changed_mind" id="reason1" required>
                                <label for="reason1" class="mb-0">
                                    <strong>Changed my mind</strong><br>
                                    <small class="text-muted">I no longer want this product</small>
                                </label>
                            </div>

                            <div class="reason-option" onclick="selectReason('found_cheaper', this)">
                                <input type="radio" name="cancellation_reason" value="found_cheaper" id="reason2">
                                <label for="reason2" class="mb-0">
                                    <strong>Found cheaper elsewhere</strong><br>
                                    <small class="text-muted">I found a better price from another seller</small>
                                </label>
                            </div>

                            <div class="reason-option" onclick="selectReason('shipping_issues', this)">
                                <input type="radio" name="cancellation_reason" value="shipping_issues" id="reason3">
                                <label for="reason3" class="mb-0">
                                    <strong>Shipping issues</strong><br>
                                    <small class="text-muted">Delivery time or shipping cost concerns</small>
                                </label>
                            </div>

                            <div class="reason-option" onclick="selectReason('product_unavailable', this)">
                                <input type="radio" name="cancellation_reason" value="product_unavailable" id="reason4">
                                <label for="reason4" class="mb-0">
                                    <strong>Product unavailable</strong><br>
                                    <small class="text-muted">The product is no longer needed or available</small>
                                </label>
                            </div>

                            <div class="reason-option" onclick="selectReason('payment_issues', this)">
                                <input type="radio" name="cancellation_reason" value="payment_issues" id="reason5">
                                <label for="reason5" class="mb-0">
                                    <strong>Payment issues</strong><br>
                                    <small class="text-muted">Problems with payment method or process</small>
                                </label>
                            </div>

                            <div class="reason-option" onclick="selectReason('other', this)">
                                <input type="radio" name="cancellation_reason" value="other" id="reason6">
                                <label for="reason6" class="mb-0">
                                    <strong>Other reason</strong><br>
                                    <small class="text-muted">Please specify below</small>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Reason Input -->
                        <div class="custom-reason mt-3" id="customReasonContainer">
                            <label for="custom_reason" class="form-label">
                                <strong>Please specify your reason:</strong>
                            </label>
                            <textarea
                                name="custom_reason"
                                id="custom_reason"
                                class="form-control"
                                rows="3"
                                placeholder="Please provide more details about why you're cancelling this order..."
                                maxlength="500"></textarea>
                            <div class="form-text">Maximum 500 characters</div>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Important:</strong> Once cancelled, this action cannot be undone.
                        You will need to place a new order if you change your mind.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back to Order
                        </a>
                        <button type="submit" class="btn btn-cancel" onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                            <i class="fas fa-times-circle"></i> Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectReason(reason, element) {
    // Remove selected class from all options
    document.querySelectorAll('.reason-option').forEach(opt => {
        opt.classList.remove('selected');
    });

    // Add selected class to clicked option
    element.classList.add('selected');

    // Check the radio button
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;

    // Show/hide custom reason input
    const customReasonContainer = document.getElementById('customReasonContainer');
    if (reason === 'other') {
        customReasonContainer.style.display = 'block';
    } else {
        customReasonContainer.style.display = 'none';
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedReason = document.querySelector('input[name="cancellation_reason"]:checked');
        if (!selectedReason) {
            e.preventDefault();
            alert('Please select a cancellation reason.');
            return;
        }

        if (selectedReason.value === 'other') {
            const customReason = document.getElementById('custom_reason').value.trim();
            if (!customReason) {
                e.preventDefault();
                alert('Please provide a reason for cancellation.');
                return;
            }
        }
    });
});
</script>
@endsection
