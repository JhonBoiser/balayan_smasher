{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('page-title', 'Categories Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-tag"></i> Categories</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="bi bi-plus-circle"></i> Add New Category
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="img-thumbnail"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-muted fs-3"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                                <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                            @endif
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $category->products_count }} products</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $category->order }}</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm table-actions" role="group">
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal{{ $category->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure? This will also affect {{ $category->products_count }} products.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-danger"
                                            title="Delete"
                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Category Modal -->
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Category Name *</label>
                                            <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="2">{{ $category->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Display Order</label>
                                            <input type="number" class="form-control" name="order" value="{{ $category->order }}" min="0">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail mb-2" style="max-height: 100px;">
                                            @endif
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" {{ $category->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No categories found</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="bi bi-plus-circle"></i> Add Your First Category
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
    <div class="card-footer bg-white">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="order" value="0" min="0">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" checked>
                        <label class="form-check-label">Active (Visible in store)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
