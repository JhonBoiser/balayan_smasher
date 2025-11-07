{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Edit Product</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Products
    </a>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
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
                        <label for="specifications" class="form-label">Specifications</label>
                        <textarea class="form-control @error('specifications') is-invalid @enderror"
                                  id="specifications"
                                  name="specifications"
                                  rows="3">{{ old('specifications', $product->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Product Images</h5>
                </div>
                <div class="card-body">
                    <!-- Existing Images -->
                    @if($product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Current Images</label>
                        <div class="row g-2">
                            @foreach($product->images as $image)
                            <div class="col-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         class="img-thumbnail"
                                         style="height: 120px; width: 100%; object-fit: cover;">
                                    @if($image->is_primary)
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-1">Primary</span>
                                    @endif
                                    <form action="{{ route('admin.products.image.delete', [$product->id, $image->id]) }}"
                                          method="POST"
                                          class="position-absolute top-0 end-0 m-1"
                                          onsubmit="return confirm('Delete this image?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upload New Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label">Add New Images</label>
                        <input type="file"
                               class="form-control @error('images.*') is-invalid @enderror"
                               id="images"
                               name="images[]"
                               multiple
                               accept="image/*">
                        <small class="text-muted">Upload additional images for this product</small>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="image-preview" class="row g-2"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pricing</h5>
                </div>
                <div class="card-body">
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

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Inventory</h5>
                </div>
                <div class="card-body">
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

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Product Status</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_active"
                               name="is_active"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (Visible in store)
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_featured"
                               name="is_featured"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Featured Product
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-save"></i> Update Product
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // Image preview for new uploads
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-3';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                        <span class="badge bg-info position-absolute top-0 start-0 m-1">New</span>
                    </div>
                `;
                preview.appendChild(col);
            };

            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
