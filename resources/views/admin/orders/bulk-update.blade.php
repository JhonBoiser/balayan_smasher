{{-- resources/views/admin/orders/bulk-update.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Bulk Update Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-arrow-repeat"></i> Bulk Update Order Status</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Select Orders to Update</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.bulk-update-status') }}" method="POST" id="bulk-update-form">
            @csrf

            <!-- Orders Table -->
            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Current Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="form-check-input order-checkbox">
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No orders found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Status Update Section -->
            <div class="row">
                <div class="col-md-6">
                    <label for="status" class="form-label">Change Status To <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                    <i class="bi bi-arrow-repeat"></i> Update Selected Orders
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const orderCheckboxes = document.querySelectorAll('.order-checkbox');
        const submitBtn = document.getElementById('submit-btn');

        // Toggle all checkboxes
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSubmitButton();
        });

        // Update submit button state
        orderCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSubmitButton();
                // Uncheck "select all" if any individual is unchecked
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                }
            });
        });

        function updateSubmitButton() {
            const anyChecked = Array.from(orderCheckboxes).some(cb => cb.checked);
            submitBtn.disabled = !anyChecked;
        }

        // Form validation
        document.getElementById('bulk-update-form').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            const anyChecked = Array.from(orderCheckboxes).some(cb => cb.checked);

            if (!anyChecked) {
                e.preventDefault();
                showDialog('Validation', 'Please select at least one order', 'warning');
                return false;
            }

            if (!status) {
                e.preventDefault();
                showDialog('Validation', 'Please select a status', 'warning');
                return false;
            }

            if (!confirm('Are you sure you want to update ' + Array.from(orderCheckboxes).filter(cb => cb.checked).length + ' order(s)?')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection
