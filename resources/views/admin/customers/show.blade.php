@extends('admin.layouts.app')

@section('title', 'Customer Profile - ' . $customer->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Customer Profile</li>
@endsection

@section('actions')
    <div class="btn-group">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('admin.customers.orders', $customer->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-bag"></i>
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Customer Profile -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($customer->avatar)
                    <img src="{{ asset('storage/' . $customer->avatar) }}" 
                         alt="{{ $customer->name }}" 
                         class="rounded-circle img-fluid" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-4x text-primary"></i>
                    </div>
                @endif
                <h5 class="my-3">{{ $customer->name }}</h5>
                <p class="text-muted mb-1">{{ $customer->email }}</p>
                <p class="text-muted mb-4">
                    @if($customer->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Unverified</span>
                    @endif
                </p>
                
                <div class="d-flex justify-content-center mb-2">
                    @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-phone"></i> Call
                        </a>
                    @endif
                    <a href="mailto:{{ $customer->email }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Customer Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">${{ number_format($stats['total_spent'], 2) }}</h4>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">${{ number_format($stats['avg_order_value'], 2) }}</h4>
                            <small class="text-muted">Avg. Order Value</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">
                                @if($stats['last_order_date'])
                                    {{ \Carbon\Carbon::parse($stats['last_order_date'])->format('M d') }}
                                @else
                                    N/A
                                @endif
                            </h4>
                            <small class="text-muted">Last Order</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Email:</strong>
                    <div>{{ $customer->email }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Phone:</strong>
                    <div>{{ $customer->phone ?? 'Not provided' }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Address:</strong>
                    <div>{{ $customer->address ?? 'Not provided' }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Member Since:</strong>
                    <div>{{ $customer->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Last Login:</strong>
                    <div>
                        @if($customer->last_login)
                            {{ \Carbon\Carbon::parse($customer->last_login)->format('M d, Y h:i A') }}
                        @else
                            Never logged in
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Orders -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="{{ route('admin.customers.orders', $customer->id) }}" class="btn btn-sm btn-primary">
                    View All Orders
                </a>
            </div>
            <div class="card-body">
                @if($customer->orders->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-2x text-muted mb-2"></i>
                        <h5>No orders yet</h5>
                        <p class="text-muted">This customer hasn't placed any orders.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ $order->items_count ?? 0 }} items</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Pending' => 'warning',
                                                'Processing' => 'info',
                                                'Shipped' => 'primary',
                                                'Delivered' => 'success',
                                                'Completed' => 'success',
                                                'Cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-light">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Account Created</h6>
                                <small class="text-muted">{{ $customer->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-1">Customer registered on the website</p>
                        </div>
                    </div>
                    
                    @if($customer->email_verified_at)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Email Verified</h6>
                                <small class="text-muted">{{ $customer->email_verified_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-1">Customer verified their email address</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($customer->last_login)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Last Login</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($customer->last_login)->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-1">Customer logged into their account</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-bottom: 20px;
}
</style>
@endpush