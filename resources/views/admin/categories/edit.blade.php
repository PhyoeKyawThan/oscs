@extends('admin.layouts.app')

@section('title', 'Edit Category - ' . $category->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $category->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" 
                                    name="parent_id">
                                <option value="">No Parent (Main Category)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Current Image</label>
                        <div class="text-center">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid rounded mb-2" 
                                     style="max-height: 150px;">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="remove_image" 
                                           name="remove_image" 
                                           value="1">
                                    <label class="form-check-label" for="remove_image">
                                        Remove current image
                                    </label>
                                </div>
                            @else
                                <div class="bg-light rounded p-4 mb-2">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                    <p class="mt-2 mb-0">No image uploaded</p>
                                </div>
                            @endif
                        </div>
                        
                        <label for="image" class="form-label mt-3">Upload New Image</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <small class="text-muted">Optional. Leave empty to keep current image</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                            <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Category Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Category Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Slug:</strong>
                    <code>{{ $category->slug }}</code>
                </div>
                
                <div class="mb-3">
                    <strong>Created:</strong>
                    <div>{{ $category->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <div>{{ $category->updated_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Products in Category:</strong>
                    <h4 class="mt-2">{{ $category->products_count }}</h4>
                </div>
            </div>
        </div>
        
        <!-- Warning -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Important Notes</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Changing parent category:</strong>
                    <p class="mb-0 mt-2">
                        If you change the parent category, make sure it doesn't create a circular reference.
                    </p>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Products:</strong>
                    <p class="mb-0 mt-2">
                        Changing category properties won't affect existing products.
                        Products will remain in this category.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview new image before upload
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

// Handle remove image checkbox
document.getElementById('remove_image')?.addEventListener('change', function() {
    const fileInput = document.getElementById('image');
    if (this.checked) {
        fileInput.required = false;
    }
});
</script>
@endpush