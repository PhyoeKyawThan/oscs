@extends('admin.layouts.app')

@section('title', 'Add New Product')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Product</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-lg-8">
                            <div class="mb-4">
                                <label for="name" class="form-label">Product Name *</label>
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
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price') }}" 
                                               required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                    <input type="number" 
                                           min="0" 
                                           class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', 0) }}" 
                                           required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">Product Status</h6>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_available" 
                                                   name="is_available" 
                                                   {{ old('is_available', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_available">
                                                Available for purchase
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_featured" 
                                                   name="is_featured" 
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured product
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border mt-4">
                                <div class="card-body">
                                    <h6 class="mb-3">Product Image</h6>
                                    
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Main Image *</label>
                                        <input type="file" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               id="image" 
                                               name="image" 
                                               accept="image/*" 
                                               required>
                                        <small class="text-muted">Max file size: 2MB. Allowed: JPG, PNG, GIF, WEBP</small>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                            <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Additional Images</label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="images" 
                                               name="images[]" 
                                               accept="image/*" 
                                               multiple>
                                        <small class="text-muted">You can select multiple images</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card border mt-4">
                                <div class="card-body">
                                    <h6 class="mb-3">Product Category</h6>
                                    
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" 
                                                name="category_id" 
                                                required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">Additional Information</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   class="form-control" 
                                                   id="weight" 
                                                   name="weight" 
                                                   value="{{ old('weight') }}">
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="dimensions" class="form-label">Dimensions (L×W×H)</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="dimensions" 
                                                   name="dimensions" 
                                                   value="{{ old('dimensions') }}" 
                                                   placeholder="e.g., 10×5×3">
                                        </div>
                                        
                                        <div class="col-12 mb-3">
                                            <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="sku" 
                                                   name="sku" 
                                                   value="{{ old('sku') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview main image before upload
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

// Auto-generate SKU if empty
document.getElementById('name').addEventListener('blur', function() {
    const skuField = document.getElementById('sku');
    if (!skuField.value) {
        // Generate SKU from name
        const name = this.value;
        const sku = name.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 10) + 
                   '-' + Math.floor(Math.random() * 1000);
        skuField.value = sku;
    }
});
</script>
@endpush