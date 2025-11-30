{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Products Management')

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Products</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Product
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @php
                                $displayImage = $product->primaryImage ?? $product->images->first() ?? null;
                            @endphp

                            @if($displayImage)
                                <img src="{{ asset('storage/' . $displayImage->image_path) }}"
                                     alt="{{ $displayImage->alt_text ?? $product->name }}"
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
                            <strong>{{ $product->name }}</strong>
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark ms-1">Featured</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>
                            @if($product->sale_price)
                                <span class="text-danger fw-bold">₱{{ number_format($product->sale_price, 2) }}</span>
                                <br>
                                <small class="text-muted text-decoration-line-through">
                                    ₱{{ number_format($product->price, 2) }}
                                </small>
                            @else
                                <span class="fw-bold">₱{{ number_format($product->price, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">Out of Stock</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      class="d-inline delete-product-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-danger delete-product-btn"
                                            title="Delete"
                                            data-product-name="{{ $product->name }}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No products found</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Your First Product
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="card-footer bg-white">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success message from backend with SweetAlert
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#28a745',
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    // Show error message from backend with SweetAlert
    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#d33'
    });
    @endif

    // Delete product confirmation - keep original form functionality
    document.querySelectorAll('.delete-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('form');
            const productName = this.dataset.productName;

            Swal.fire({
                title: 'Delete Product?',
                html: `Are you sure you want to delete <strong>"${productName}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form normally - keep original functionality
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
