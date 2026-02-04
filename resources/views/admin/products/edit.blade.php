@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Product</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-lg-8">
                            <div class="mb-4">
                                <label for="name" class="form-label">Product Name *</label>
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
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          required>{{ old('description', $product->description) }}</textarea>
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
                                               value="{{ old('price', $product->price) }}" 
                                               required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="stock" class="form-label">Stock Quantity *</label>
                                    <input type="number" 
                                           min="0" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock', $product->stock) }}" 
                                           required>
                                    @error('stock')
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
                                                   {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
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
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
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
                                    
                                    <!-- Current Image -->
                                    <div class="mb-3">
                                        <label class="form-label">Current Image</label>
                                        <div class="text-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
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
                                    </div>
                                    
                                    <!-- New Image -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload New Image</label>
                                        <input type="file" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               id="image" 
                                               name="image" 
                                               accept="image/*">
                                        <small class="text-muted">Leave empty to keep current image</small>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                            <img id="previewImage" class="img-fluid rounded" style="max-height: 150px;">
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Images -->
                                    <div class="mb-3">
                                        <label class="form-label">Additional Images</label>
                                        
                                        @if($product->images && is_array(json_decode($product->images, true)))
                                            <div class="mb-2">
                                                @foreach(json_decode($product->images, true) as $index => $additionalImage)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <img src="{{ asset('storage/' . $additionalImage) }}" 
                                                             alt="Additional Image {{ $index + 1 }}" 
                                                             class="rounded me-2" 
                                                             width="50" 
                                                             height="50" 
                                                             style="object-fit: cover;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   id="remove_image_{{ $index }}" 
                                                                   name="remove_additional_images[]" 
                                                                   value="{{ $index }}">
                                                            <label class="form-check-label" for="remove_image_{{ $index }}">
                                                                Remove
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <input type="file" 
                                               class="form-control" 
                                               id="images" 
                                               name="images[]" 
                                               accept="image/*" 
                                               multiple>
                                        <small class="text-muted">Add more images</small>
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
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                                   value="{{ old('weight', $product->weight) }}">
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="dimensions" class="form-label">Dimensions (L×W×H)</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="dimensions" 
                                                   name="dimensions" 
                                                   value="{{ old('dimensions', $product->dimensions) }}" 
                                                   placeholder="e.g., 10×5×3">
                                        </div>
                                        
                                        <div class="col-12 mb-3">
                                            <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="sku" 
                                                   name="sku" 
                                                   value="{{ old('sku', $product->sku) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Product
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

// Handle remove current image checkbox
document.getElementById('remove_image')?.addEventListener('change', function() {
    const fileInput = document.getElementById('image');
    if (this.checked) {
        fileInput.required = true;
    } else {
        fileInput.required = false;
    }
});
</script>
@endpush