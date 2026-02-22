@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">Products</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Edit Product</span>
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">Edit Product</h5>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <!-- Basic Information -->
                        <div class="lg:col-span-8">
                            <!-- Product Name -->
                            <div class="mb-5">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Description -->
                            <div class="mb-5">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Price and Stock -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                        Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0" 
                                               class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price', $product->price) }}" 
                                               required>
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Stock Quantity -->
                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                                        Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           min="0" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock', $product->stock) }}" 
                                           required>
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Additional Information Card -->
                            <div class="border border-gray-200 rounded-lg p-5 mt-6">
                                <h6 class="text-base font-semibold text-gray-900 mb-4">Additional Information</h6>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Weight -->
                                    <div class="mb-3">
                                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               id="weight" 
                                               name="weight" 
                                               value="{{ old('weight', $product->weight) }}">
                                    </div>
                                    
                                    <!-- SKU -->
                                    <div class="mb-3">
                                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                        <input type="text" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               id="sku" 
                                               name="sku" 
                                               value="{{ old('sku', $product->sku) }}"
                                               placeholder="Auto-generated if empty">
                                    </div>
                                    
                                    <!-- Dimensions -->
                                    <div class="col-span-1 md:col-span-2 mb-3">
                                        <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensions (L×W×H)</label>
                                        <input type="text" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               id="dimensions" 
                                               name="dimensions" 
                                               value="{{ old('dimensions', $product->dimensions) }}" 
                                               placeholder="e.g., 10×5×3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sidebar -->
                        <div class="lg:col-span-4">
                            <!-- Product Status Card -->
                            <div class="border border-gray-200 rounded-lg p-5 mb-5">
                                <h6 class="text-base font-semibold text-gray-900 mb-3">Product Status</h6>
                                
                                <div class="space-y-4">
                                    <!-- Available Switch -->
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm text-gray-700" for="is_available">
                                            Available for purchase
                                        </label>
                                        <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                            <input type="checkbox" 
                                                   id="is_available" 
                                                   name="is_available" 
                                                   class="sr-only peer"
                                                   {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Featured Switch -->
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm text-gray-700" for="is_featured">
                                            Featured product
                                        </label>
                                        <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                            <input type="checkbox" 
                                                   id="is_featured" 
                                                   name="is_featured" 
                                                   class="sr-only peer"
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Image Card -->
                            <div class="border border-gray-200 rounded-lg p-5 mb-5">
                                <h6 class="text-base font-semibold text-gray-900 mb-3">Product Image</h6>
                                
                                <!-- Current Image -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                    <div class="text-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="max-h-36 rounded-lg mx-auto mb-3 border border-gray-200"
                                                 style="max-height: 150px;">
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" 
                                                       id="remove_image" 
                                                       name="remove_image" 
                                                       value="1"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <label for="remove_image" class="ml-2 text-sm text-gray-700">
                                                    Remove current image
                                                </label>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 rounded-lg p-6 mb-2">
                                                <i class="fas fa-image fa-3x text-gray-400"></i>
                                                <p class="mt-2 text-sm text-gray-500">No image uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- New Image -->
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                                    <input type="file" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*">
                                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-3 text-center hidden">
                                        <img id="previewImage" class="max-h-36 rounded-lg mx-auto border border-gray-200">
                                    </div>
                                </div>
                                
                                <!-- Additional Images -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                                    
                                    @if($product->images && is_array(json_decode($product->images, true)))
                                        <div class="space-y-2 mb-3">
                                            @foreach(json_decode($product->images, true) as $index => $additionalImage)
                                                <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                                                    <img src="{{ asset('storage/' . $additionalImage) }}" 
                                                         alt="Additional Image {{ $index + 1 }}" 
                                                         class="rounded-lg mr-3 w-12 h-12 object-cover">
                                                    <div class="flex items-center flex-1">
                                                        <input type="checkbox" 
                                                               id="remove_image_{{ $index }}" 
                                                               name="remove_additional_images[]" 
                                                               value="{{ $index }}"
                                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                        <label for="remove_image_{{ $index }}" class="ml-2 text-sm text-gray-700">
                                                            Remove this image
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <input type="file" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                           id="images" 
                                           name="images[]" 
                                           accept="image/*" 
                                           multiple>
                                    <p class="mt-1 text-xs text-gray-500">Add more images</p>
                                </div>
                            </div>
                            
                            <!-- Product Category Card -->
                            <div class="border border-gray-200 rounded-lg p-5">
                                <h6 class="text-base font-semibold text-gray-900 mb-3">Product Category</h6>
                                
                                <div class="mb-3">
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white @error('category_id') border-red-500 @enderror" 
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
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
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
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.classList.add('hidden');
                previewImage.src = '';
            }
        });
    }
    
    // Handle remove current image checkbox
    const removeImageCheckbox = document.getElementById('remove_image');
    const newImageInput = document.getElementById('image');
    
    if (removeImageCheckbox && newImageInput) {
        removeImageCheckbox.addEventListener('change', function() {
            // When "remove current image" is checked, make new image optional
            // When unchecked, new image is optional as well (it's always optional in edit mode)
            // This is just for UI feedback
            if (this.checked) {
                // Optionally show a message or highlight the new image input
                newImageInput.classList.add('border-yellow-400');
            } else {
                newImageInput.classList.remove('border-yellow-400');
            }
        });
    }
});
</script>
@endpush