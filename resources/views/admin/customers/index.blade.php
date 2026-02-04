@extends('admin.layouts.app')

@section('title', 'Customers Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('actions')
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Customer
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">All Customers</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search customers..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ms-2">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light ms-2">
                        <i class="fas fa-redo"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if($customers->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>No customers found</h5>
                <p class="text-muted">When customers register, they'll appear here.</p>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Customer
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($customer->avatar)
                                        <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                             alt="{{ $customer->name }}" 
                                             class="rounded-circle me-3" 
                                             width="40" 
                                             height="40" 
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $customer->name }}</h6>
                                        <small class="text-muted">ID: {{ $customer->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                    {{ $customer->email }}
                                </a>
                            </td>
                            <td>
                                {{ $customer->phone ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $customer->orders_count }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $totalSpent = \App\Models\Orders::where('user_id', $customer->id)
                                        ->where('status', 'Completed')
                                        ->sum('total_amount');
                                @endphp
                                <strong>${{ number_format($totalSpent, 2) }}</strong>
                            </td>
                            <td>
                                {{ $customer->created_at->format('M d, Y') }}
                                <div class="text-muted small">{{ $customer->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                @if($customer->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Unverified</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                       class="btn btn-sm btn-light" 
                                       data-bs-toggle="tooltip" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                                       class="btn btn-sm btn-light" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('admin.customers.destroy', $customer->id) }}" 
                                          id="delete-customer-{{ $customer->id }}"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger" 
                                                data-bs-toggle="tooltip" 
                                                title="Delete"
                                                onclick="confirmDelete(event, 'delete-customer-{{ $customer->id }}')">
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
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Statistics -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Total Customers</h6>
                        <h3 class="fw-bold mb-0">{{ $customers->total() }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Active Today</h6>
                        <h3 class="fw-bold mb-0">
                            {{ \App\Models\User::whereDate('last_login', today())->count() }}
                        </h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">New This Month</h6>
                        <h3 class="fw-bold mb-0">
                            {{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}
                        </h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Verified Users</h6>
                        <h3 class="fw-bold mb-0">
                            {{ \App\Models\User::whereNotNull('email_verified_at')->count() }}
                        </h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection