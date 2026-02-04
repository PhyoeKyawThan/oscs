@extends('admin.layouts.app')

@section('title', 'Categories Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Category
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Categories</h5>
            </div>
            <div class="card-body">
                @if($categories->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5>No categories found</h5>
                        <p class="text-muted">Add your first category to organize products.</p>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Category
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="rounded me-3" 
                                                     width="40" 
                                                     height="40" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-folder text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $category->name }}</h6>
                                                @if($category->parent)
                                                    <small class="text-muted">Parent: {{ $category->parent->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $category->products_count }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                               class="btn btn-sm btn-light" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.categories.destroy', $category->id) }}" 
                                                  id="delete-category-{{ $category->id }}"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-light text-danger" 
                                                        data-bs-toggle="tooltip" 
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                        </div>
                        <div>
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Category Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Category Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $categories->total() }}</h4>
                            <small class="text-muted">Total Categories</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $categories->where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Active Categories</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $categories->whereNull('parent_id')->count() }}</h4>
                            <small class="text-muted">Main Categories</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $categories->whereNotNull('parent_id')->count() }}</h4>
                            <small class="text-muted">Sub Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Tips -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Categories help organize products
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        You can create nested categories
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Categories can be enabled/disabled
                    </li>
                    <li>
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Deleting a category won't delete products
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection