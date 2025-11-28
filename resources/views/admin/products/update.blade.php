{{-- resources/views/admin/products/update.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Update Product')

@section('content')
<div class="container-fluid">

    {{-- Add this to your layout or content section --}}
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-pencil-square"></i> Update Product
        </h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="update-product-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="mb-4">
                                    <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       id="name"
                                                       name="name"
                                                       value="{{ old('name', $product->name) }}"
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('sku') is-invalid @enderror"
                                                       id="sku"
                                                       name="sku"
                                                       value="{{ old('sku', $product->sku) }}"
                                                       required>
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id"
                                                name="category_id"
                                                required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  name="description"
                                                  rows="4">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="specifications" class="form-label">Specifications (JSON)</label>
                                        <textarea class="form-control @error('specifications') is-invalid @enderror"
                                                  id="specifications"
                                                  name="specifications"
                                                  rows="3"
                                                  placeholder='{"key": "value", "color": "red"}'>{{ old('specifications', $product->specifications) }}</textarea>
                                        <small class="text-muted">Enter specifications as JSON key-value pairs</small>
                                        @error('specifications')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Pricing Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3 border-bottom pb-2">Pricing</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Regular Price (₱) <span class="text-danger">*</span></label>
                                                <input type="number"
                                                       class="form-control @error('price') is-invalid @enderror"
                                                       id="price"
                                                       name="price"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old('price', $product->price) }}"
                                                       required>
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sale_price" class="form-label">Sale Price (₱)</label>
                                                <input type="number"
                                                       class="form-control @error('sale_price') is-invalid @enderror"
                                                       id="sale_price"
                                                       name="sale_price"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old('sale_price', $product->sale_price) }}">
                                                <small class="text-muted">Leave empty if no sale</small>
                                                @error('sale_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3 border-bottom pb-2">Inventory</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number"
                                                       class="form-control @error('stock') is-invalid @enderror"
                                                       id="stock"
                                                       name="stock"
                                                       min="0"
                                                       value="{{ old('stock', $product->stock) }}"
                                                       required>
                                                @error('stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="low_stock_threshold" class="form-label">Low Stock Alert</label>
                                                <input type="number"
                                                       class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                                       id="low_stock_threshold"
                                                       name="low_stock_threshold"
                                                       min="0"
                                                       value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                                                <small class="text-muted">Alert when stock reaches this level</small>
                                                @error('low_stock_threshold')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <!-- Status Settings -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Product Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="is_active"
                                                   name="is_active"
                                                   value="1"
                                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                Active Product
                                            </label>
                                            <small class="d-block text-muted">Visible in storefront</small>
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="is_featured"
                                                   name="is_featured"
                                                   value="1"
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_featured">
                                                Featured Product
                                            </label>
                                            <small class="d-block text-muted">Show on featured sections</small>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="track_stock"
                                                   name="track_stock"
                                                   value="1"
                                                   {{ old('track_stock', $product->track_stock) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="track_stock">
                                                Track Stock
                                            </label>
                                            <small class="d-block text-muted">Enable stock management</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Images -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Product Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Images -->
                                        @if($product->images->count() > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Current Images</label>
                                                <div class="row g-2" id="current-images">
                                                    @foreach($product->images as $image)
                                                        <div class="col-6">
                                                            <div class="position-relative border rounded p-1">
                                                                <img src="{{ Storage::exists($image->image_path) ? asset('storage/' . $image->image_path) : '/images/placeholder.jpg' }}"
                                                                     class="img-fluid rounded"
                                                                     alt="Product Image">
                                                                @if($image->is_primary)
                                                                    <span class="badge bg-primary position-absolute top-0 start-0 m-1">Primary</span>
                                                                @endif
                                                                <div class="mt-2 text-center">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-outline-primary set-primary"
                                                                            data-image-id="{{ $image->id }}">
                                                                        Set Primary
                                                                    </button>
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-outline-danger delete-image"
                                                                            data-image-id="{{ $image->id }}">
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Upload New Images -->
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload New Images</label>
                                            <input type="file"
                                                   class="form-control @error('images.*') is-invalid @enderror"
                                                   id="images"
                                                   name="images[]"
                                                   multiple
                                                   accept="image/*">
                                            <small class="text-muted">Select multiple images (Max: 5MB each)</small>
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Image Preview -->
                                        <div id="image-preview" class="row g-2 mt-2"></div>
                                    </div>
                                </div>

                                <!-- Product Metrics -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Product Metrics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="small">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Created:</span>
                                                <span>{{ $product->created_at->format('M j, Y') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Last Updated:</span>
                                                <span>{{ $product->updated_at->format('M j, Y') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Views:</span>
                                                <span>{{ $product->views_count ?? 0 }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Orders:</span>
                                                <span>{{ $product->orders_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="submit-btn">
                                            <i class="bi bi-save"></i> Update Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All product data and images will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('update-product-form');
    const submitBtn = document.getElementById('submit-btn');

    // Form submission handler
    form.addEventListener('submit', function(e) {
        // Prevent double submission
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
    });

    // Image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showDialog('File Too Large', 'File ' + file.name + ' is too large. Maximum size is 5MB.', 'error');
                continue;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-6 mb-2';
                col.innerHTML = `
                    <div class="border rounded p-2">
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 100px;">
                        <div class="mt-1 small text-muted">${file.name}</div>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });

    // Set primary image
    document.querySelectorAll('.set-primary').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            if (confirm('Set this image as primary?')) {
                fetch(`/admin/products/{{ $product->id }}/images/${imageId}/set-primary`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        showDialog('Error', 'Error setting primary image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showDialog('Error', 'Error setting primary image', 'error');
                });
            }
        });
    });

    // Delete image
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            if (confirm('Delete this image?')) {
                fetch(`/admin/products/{{ $product->id }}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`[data-image-id="${imageId}"]`).closest('.col-6').remove();
                    } else {
                        showDialog('Error', 'Error deleting image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showDialog('Error', 'Error deleting image', 'error');
                });
            }
        });
    });

    // Price validation
    const salePrice = document.getElementById('sale_price');
    const regularPrice = document.getElementById('price');

    salePrice.addEventListener('change', function() {
        if (this.value && parseFloat(this.value) >= parseFloat(regularPrice.value)) {
            showDialog('Price Error', 'Sale price must be less than regular price', 'error');
            this.value = '';
        }
    });
});
</script>
@endsection

@section('styles')
<style>
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
.img-thumbnail {
    max-height: 150px;
    object-fit: cover;
}
</style>
@endsection
