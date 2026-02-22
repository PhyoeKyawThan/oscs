@extends('admin.layouts.app')

@section('title', 'Categories Management')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Categories</span>
    </li>
@endsection

@section('actions')
    <a href="{{ route('admin.categories.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i> Add Category
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Categories Table Column -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">All Categories</h5>
            </div>
            
            <div class="p-4 sm:p-6">
                @if($categories->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-tags fa-4x text-gray-300 mb-4"></i>
                        <h5 class="text-xl font-semibold text-gray-700 mb-2">No categories found</h5>
                        <p class="text-gray-500 mb-4">Add your first category to organize products.</p>
                        <a href="{{ route('admin.categories.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i> Add Category
                        </a>
                    </div>
                @else
                    <!-- Table Container with Horizontal Scroll -->
                    <div class="-mx-4 sm:-mx-6 overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Name</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Slug</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Products</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex items-center">
                                                @if($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                                         alt="{{ $category->name }}" 
                                                         class="rounded-lg mr-3 w-10 h-10 object-cover">
                                                @else
                                                    <div class="bg-gray-100 rounded-lg mr-3 flex items-center justify-center w-10 h-10">
                                                        <i class="fas fa-folder text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="min-w-[150px]">
                                                    <h6 class="text-sm font-medium text-gray-900">{{ $category->name }}</h6>
                                                    @if($category->parent)
                                                        <small class="text-xs text-gray-500">Parent: {{ $category->parent->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <code class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $category->products_count }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            @if($category->is_active)
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                                   class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                                   data-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" 
                                                      action="{{ route('admin.categories.destroy', $category->id) }}" 
                                                      id="delete-category-{{ $category->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="p-2 bg-gray-100 hover:bg-gray-200 text-red-600 rounded-lg transition-colors duration-200"
                                                            data-toggle="tooltip" 
                                                            title="Delete"
                                                            onclick="confirmDelete(event, 'delete-category-{{ $category->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                        <div class="text-sm text-gray-500">
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                        </div>
                        <div class="flex justify-center">
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar Column -->
    <div class="lg:col-span-4">
        <!-- Category Statistics Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Category Statistics</h6>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="text-xl font-bold text-gray-900">{{ $categories->total() }}</h4>
                        <p class="text-xs text-gray-500">Total Categories</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="text-xl font-bold text-gray-900">{{ $categories->where('is_active', true)->count() }}</h4>
                        <p class="text-xs text-gray-500">Active Categories</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="text-xl font-bold text-gray-900">{{ $categories->whereNull('parent_id')->count() }}</h4>
                        <p class="text-xs text-gray-500">Main Categories</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="text-xl font-bold text-gray-900">{{ $categories->whereNotNull('parent_id')->count() }}</h4>
                        <p class="text-xs text-gray-500">Sub Categories</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Tips Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Tips</h6>
            </div>
            <div class="p-4">
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <span class="text-sm text-gray-600">Categories help organize products</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <span class="text-sm text-gray-600">You can create nested categories</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <span class="text-sm text-gray-600">Categories can be enabled/disabled</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <span class="text-sm text-gray-600">Deleting a category won't delete products</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Additional Info Card (Optional) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Category Hierarchy</h6>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    @php
                        $mainCategories = $categories->whereNull('parent_id');
                    @endphp
                    @foreach($mainCategories->take(5) as $mainCategory)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ $mainCategory->name }}</span>
                            <span class="text-xs text-gray-500">{{ $mainCategory->products_count }} products</span>
                        </div>
                        @foreach($categories->where('parent_id', $mainCategory->id)->take(2) as $subCategory)
                            <div class="flex items-center justify-between pl-4">
                                <span class="text-xs text-gray-600">↳ {{ $subCategory->name }}</span>
                                <span class="text-xs text-gray-400">{{ $subCategory->products_count }}</span>
                            </div>
                        @endforeach
                        @if($categories->where('parent_id', $mainCategory->id)->count() > 2)
                            <div class="text-xs text-gray-400 pl-4">
                                + {{ $categories->where('parent_id', $mainCategory->id)->count() - 2 }} more
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, formId) {
    event.preventDefault();
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        document.getElementById(formId).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if needed
    const tooltipTriggers = document.querySelectorAll('[data-toggle="tooltip"]');
    // You can implement tooltips here if needed
});
</script>
@endpush