@extends('admin.layouts.app')

@section('title', 'Add New Category')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">Categories</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Add New</span>
    </li>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Main Form Column -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">Add New Category</h5>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Category Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
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
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Parent Category and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <!-- Parent Category -->
                        
                        
                        <!-- Status Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="flex items-center h-[42px]">
                                <div class="relative inline-block w-12 h-6 rounded-full cursor-pointer">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           class="sr-only peer"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </div>
                                <label for="is_active" class="ml-3 text-sm text-gray-700">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Image -->
                    <div class="mb-5">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <p class="mt-1 text-xs text-gray-500">Optional. Max file size: 2MB. Allowed: JPG, PNG, GIF, WEBP</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3 text-center hidden">
                            <img id="previewImage" class="max-h-48 rounded-lg mx-auto border border-gray-200">
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i> Save Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tips Column -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Category Tips</h6>
            </div>
            <div class="p-6 space-y-4">
                <!-- Good Names Alert -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-blue-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Good category names:</p>
                            <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                                <li>Be descriptive and specific</li>
                                <li>Use common terms customers understand</li>
                                <li>Keep it concise</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Avoid Alert -->
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-amber-800">Avoid:</p>
                            <ul class="mt-2 text-sm text-amber-700 list-disc list-inside space-y-1">
                                <li>Using internal codes or jargon</li>
                                <li>Very long category names</li>
                                <li>Special characters</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Example Categories Alert -->
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-green-800">Example categories:</p>
                            <ul class="mt-2 text-sm text-green-700 list-disc list-inside space-y-1">
                                <li>Electronics</li>
                                <li>Home & Garden</li>
                                <li>Clothing & Accessories</li>
                                <li>Books & Media</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Slug Preview (Optional Enhancement) -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-link text-gray-400 mt-0.5 mr-3"></i>
                        <div class="w-full">
                            <p class="text-sm font-medium text-gray-700 mb-2">Slug Preview:</p>
                            <div id="slugPreview" class="text-xs text-gray-500 bg-white p-2 rounded border border-gray-200">
                                Enter a name to generate slug
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview image before upload
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slugPreview');
    
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
    
    // Auto-generate slug preview from name
    if (nameInput && slugPreview) {
        nameInput.addEventListener('input', function() {
            const name = this.value.trim();
            
            if (name) {
                // Generate slug
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                
                slugPreview.textContent = slug || 'Enter a name to generate slug';
                slugPreview.classList.remove('text-gray-500');
                slugPreview.classList.add('text-blue-600', 'font-mono');
            } else {
                slugPreview.textContent = 'Enter a name to generate slug';
                slugPreview.classList.remove('text-blue-600', 'font-mono');
                slugPreview.classList.add('text-gray-500');
            }
        });
        
        // Trigger once to show preview if there's a value
        if (nameInput.value) {
            nameInput.dispatchEvent(new Event('input'));
        }
    }
});
</script>
@endpush