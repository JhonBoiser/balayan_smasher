{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Create Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-plus-circle"></i> Create New Category</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Categories
    </a>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        <small class="text-muted">Optional: Upload a category image</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="image-preview" class="mb-3"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="order" class="form-label">Display Order</label>
                        <input type="number"
                               class="form-control @error('order') is-invalid @enderror"
                               id="order"
                               name="order"
                               min="0"
                               value="{{ old('order', 0) }}">
                        <small class="text-muted">Lower numbers appear first</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_active"
                               name="is_active"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (Visible in store)
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-save"></i> Create Category
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('image-preview');

        if (!imageInput || !preview) return;

        imageInput.addEventListener('change', function(evt) {
            preview.innerHTML = '';

            const files = evt.target.files || [];
            if (files.length === 0) return;

            const file = files[0];
            if (!file.type.startsWith('image/')) {
                preview.innerHTML = '<div class="alert alert-warning">Please select a valid image file</div>';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(loadEvent) {
                preview.innerHTML = '<img src="' + loadEvent.target.result + '" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">';
            };

            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
