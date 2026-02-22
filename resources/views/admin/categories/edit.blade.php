@extends('admin.layouts.app')

@section('title', 'Edit Category - ' . $category->name)
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">Categories</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Edit Category</span>
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Main Form Column -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">Edit Category</h5>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Category Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $category->name) }}" 
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-5">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Parent Category and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <!-- Parent Category -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white @error('parent_id') border-red-500 @enderror" 
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
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Status Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex items-center h-[42px]">
                                <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           class="sr-only peer"
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </div>
                                <label for="is_active" class="ml-3 text-sm text-gray-700">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Images -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <div class="text-center">
                            @if($category->image)
                                <div class="inline-block relative">
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="max-h-36 rounded-lg mx-auto mb-3 border border-gray-200">
                                    <div class="flex items-center justify-center">
                                        <input type="checkbox" 
                                               id="remove_image" 
                                               name="remove_image" 
                                               value="1"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <label for="remove_image" class="ml-2 text-sm text-gray-700">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-6 mb-2 inline-block">
                                    <i class="fas fa-image fa-3x text-gray-400"></i>
                                    <p class="mt-2 text-sm text-gray-500">No image uploaded</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- New Image Upload -->
                        <div class="mt-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">Optional. Leave empty to keep current image</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- New Image Preview -->
                            <div id="imagePreview" class="mt-3 text-center hidden">
                                <img id="previewImage" class="max-h-36 rounded-lg mx-auto border border-gray-200">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i> Update Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Column -->
    <div class="lg:col-span-4">
        <!-- Category Info Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Category Information</h6>
            </div>
            <div class="p-6 space-y-4">
                <!-- Slug -->
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</span>
                    <div class="mt-1">
                        <code class="text-sm text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                    </div>
                </div>
                
                <!-- Created Date -->
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $category->created_at->format('M d, Y') }}</p>
                </div>
                
                <!-- Last Updated -->
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $category->updated_at->format('M d, Y') }}</p>
                </div>
                
                <!-- Products Count -->
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Products in Category</span>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $category->products_count }}</p>
                </div>
            </div>
        </div>
        
        <!-- Important Notes Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Important Notes</h6>
            </div>
            <div class="p-6 space-y-4">
                <!-- Warning Alert -->
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-amber-800">Changing parent category:</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Make sure it doesn't create a circular reference.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Info Alert -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Products:</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Changing category properties won't affect existing products. Products will remain in this category.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Tip -->
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-green-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-green-800">Tip:</p>
                            <p class="text-sm text-green-700 mt-1">
                                Use descriptive names that customers will understand and search for.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions (Optional) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Quick Actions</h6>
            </div>
            <div class="p-6">
                <div class="space-y-2">
                    <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" 
                       class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <span class="text-sm text-gray-700">View products in this category</span>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </a>
                    <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" 
                       class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <span class="text-sm text-gray-700">Add product to this category</span>
                        <i class="fas fa-plus text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const removeImageCheckbox = document.getElementById('remove_image');
    const nameInput = document.getElementById('name');
    
    // Preview new image before upload
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
    
    // Handle remove image checkbox
    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function() {
            const fileInput = document.getElementById('image');
            if (this.checked) {
                // Optional: Show a message or highlight that new image is optional
                fileInput.classList.add('border-amber-300');
            } else {
                fileInput.classList.remove('border-amber-300');
            }
        });
    }
    
    // Real-time slug preview (optional enhancement)
    if (nameInput) {
        const slugDisplay = document.createElement('div');
        slugDisplay.className = 'mt-1 text-xs text-gray-500';
        nameInput.parentNode.appendChild(slugDisplay);
        
        nameInput.addEventListener('input', function() {
            const name = this.value.trim();
            if (name) {
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugDisplay.innerHTML = `Slug will be: <code class="bg-gray-100 px-1 rounded">${slug}</code>`;
            } else {
                slugDisplay.innerHTML = '';
            }
        });
    }
});
</script>
@endpush