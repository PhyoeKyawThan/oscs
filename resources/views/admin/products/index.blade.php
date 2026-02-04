@extends('admin.layouts.app')

@section('title', 'Products Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Product
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Bulk Actions</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.bulk') }}" id="bulkForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select" required>
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="update_status">Update Status</option>
                            <option value="update_category">Update Category</option>
                        </select>
                    </div>
                    
                    <div id="statusField" class="mb-3 d-none">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    
                    <div id="categoryField" class="mb-3 d-none">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100" disabled id="bulkSubmit">
                        Apply to Selected
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Products ({{ $products->total() }})</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search products..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5>No products found</h5>
                        <p class="text-muted">Add your first product to start selling.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Product
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               class="product-checkbox" 
                                               name="products[]" 
                                               value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="rounded me-3" 
                                                     width="40" 
                                                     height="40" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $product->name }}</h6>
                                                <small class="text-muted">{{ $product->sku ?? 'No SKU' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($product->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($product->stock_quantity <= 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->stock_quantity < 10)
                                            <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_available)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-secondary">Unavailable</span>
                                        @endif
                                        
                                        @if($product->is_featured)
                                            <span class="badge bg-info ms-1">Featured</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-light" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.products.destroy', $product->id) }}" 
                                                  id="delete-product-{{ $product->id }}"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-light text-danger" 
                                                        data-bs-toggle="tooltip" 
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                        </div>
                        <div>
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
    const actionSelect = document.querySelector('select[name="action"]');
    const statusField = document.getElementById('statusField');
    const categoryField = document.getElementById('categoryField');
    
    // Select all checkbox
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkSubmit();
    });
    
    // Individual checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkSubmit);
    });
    
    // Update bulk submit button state
    function updateBulkSubmit() {
        const checked = document.querySelectorAll('.product-checkbox:checked');
        bulkSubmit.disabled = checked.length === 0;
    }
    
    // Show/hide additional fields based on action
    actionSelect.addEventListener('change', function() {
        const action = this.value;
        
        // Hide all fields first
        statusField.classList.add('d-none');
        categoryField.classList.add('d-none');
        
        // Show relevant field
        if (action === 'update_status') {
            statusField.classList.remove('d-none');
        } else if (action === 'update_category') {
            categoryField.classList.remove('d-none');
        }
    });
    
    // Confirm bulk delete
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const action = document.querySelector('select[name="action"]').value;
        const checked = document.querySelectorAll('.product-checkbox:checked').length;
        
        if (action === 'delete' && checked > 0) {
            if (!confirm(`Are you sure you want to delete ${checked} product(s)? This action cannot be undone.`)) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endpush