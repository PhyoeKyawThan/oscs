@extends('admin.layouts.app')

@section('title', 'Products Management')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Products</span>
    </li>
@endsection

@section('actions')
    <a href="{{ route('admin.products.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Filters Column -->
    <div class="lg:col-span-3">
        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Filters</h6>
            </div>
            <div class="p-4">
                <form method="GET">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm">
                            <option value="all">All Status</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        </select>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bulk Actions Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Bulk Actions</h6>
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('admin.products.bulk') }}" id="bulkForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select name="action" id="bulkActionSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm" required>
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="update_status">Update Status</option>
                            <option value="update_category">Update Category</option>
                        </select>
                    </div>
                    
                    <div id="statusField" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    
                    <div id="categoryField" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" id="bulkSubmit" disabled>
                        Apply to Selected
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Products Table Column -->
    <div class="lg:col-span-9">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h5 class="text-lg font-semibold text-gray-900">Products ({{ $products->total() }})</h5>
                    
                    <form method="GET" class="flex w-full sm:w-auto">
                        <input type="text" 
                               name="search" 
                               class="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" 
                               placeholder="Search products..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-4 sm:p-6">
                @if($products->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-box fa-4x text-gray-300 mb-4"></i>
                        <h5 class="text-xl font-semibold text-gray-700 mb-2">No products found</h5>
                        <p class="text-gray-500 mb-4">Add your first product to start selling.</p>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i> Add Product
                        </a>
                    </div>
                @else
                    <!-- Table Container with Horizontal Scroll -->
                    <div class="-mx-4 sm:-mx-6 overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-12">
                                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Product</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Category</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Price</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Stock</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" 
                                                   class="product-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                                   name="products[]" 
                                                   value="{{ $product->id }}">
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="rounded-lg mr-3 w-10 h-10 object-cover">
                                                @else
                                                    <div class="bg-gray-100 rounded-lg mr-3 flex items-center justify-center w-10 h-10">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="min-w-[150px]">
                                                    <h6 class="text-sm font-medium text-gray-900">{{ $product->name }}</h6>
                                                    <small class="text-xs text-gray-500">{{ $product->sku ?? 'No SKU' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                {{ $product->category->name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            @if($product->stock_quantity <= 0)
                                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Out of Stock</span>
                                            @elseif($product->stock_quantity < 10)
                                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Low Stock ({{ $product->stock_quantity }})</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">{{ $product->stock_quantity }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex gap-1">
                                                @if($product->is_available)
                                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Available</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unavailable</span>
                                                @endif
                                                
                                                @if($product->is_featured)
                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Featured</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                                   data-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form method="POST" 
                                                      action="{{ route('admin.products.destroy', $product->id) }}" 
                                                      id="delete-product-{{ $product->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="p-2 bg-gray-100 hover:bg-gray-200 text-red-600 rounded-lg transition-colors duration-200"
                                                            data-toggle="tooltip" 
                                                            title="Delete"
                                                            onclick="confirmDelete(event, 'delete-product-{{ $product->id }}')">
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
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                        </div>
                        <div class="flex justify-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk actions
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkSubmit = document.getElementById('bulkSubmit');
    const actionSelect = document.getElementById('bulkActionSelect');
    const statusField = document.getElementById('statusField');
    const categoryField = document.getElementById('categoryField');
    
    // Select all checkbox
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkSubmit();
        });
    }
    
    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkSubmit);
    });
    
    // Update bulk submit button state
    function updateBulkSubmit() {
        const checked = document.querySelectorAll('.product-checkbox:checked');
        if (bulkSubmit) {
            bulkSubmit.disabled = checked.length === 0;
        }
    }
    
    // Show/hide additional fields based on action
    if (actionSelect) {
        actionSelect.addEventListener('change', function() {
            const action = this.value;
            
            // Hide all fields first
            if (statusField) statusField.classList.add('hidden');
            if (categoryField) categoryField.classList.add('hidden');
            
            // Show relevant field
            if (action === 'update_status' && statusField) {
                statusField.classList.remove('hidden');
            } else if (action === 'update_category' && categoryField) {
                categoryField.classList.remove('hidden');
            }
        });
    }
    
    // Confirm bulk delete
    const bulkForm = document.getElementById('bulkForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const action = document.querySelector('select[name="action"]')?.value;
            const checked = document.querySelectorAll('.product-checkbox:checked').length;
            
            if (action === 'delete' && checked > 0) {
                if (!confirm(`Are you sure you want to delete ${checked} product(s)? This action cannot be undone.`)) {
                    e.preventDefault();
                }
            }
        });
    }
});

// Confirm delete function (kept from original)
function confirmDelete(event, formId) {
    event.preventDefault();
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        document.getElementById(formId).submit();
    }
}
</script>
@endpush