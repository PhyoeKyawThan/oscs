@extends('admin.layouts.app')

@section('title', 'Add New Category')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">Category Name *</label>
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
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
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
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <small class="text-muted">Optional. Max file size: 2MB. Allowed: JPG, PNG, GIF, WEBP</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                            <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Category
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
        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Category Tips</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Good category names:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Be descriptive and specific</li>
                        <li>Use common terms customers understand</li>
                        <li>Keep it concise</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Avoid:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Using internal codes or jargon</li>
                        <li>Very long category names</li>
                        <li>Special characters</li>
                    </ul>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Example categories:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Electronics</li>
                        <li>Home & Garden</li>
                        <li>Clothing & Accessories</li>
                        <li>Books & Media</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview image before upload
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

// Auto-generate slug from name
document.getElementById('name').addEventListener('blur', function() {
    const name = this.value;
    if (name) {
        // Simple slug generation (you might want to improve this)
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        
        // You can display it somewhere or use it for validation
        console.log('Generated slug:', slug);
    }
});
</script>
@endpush