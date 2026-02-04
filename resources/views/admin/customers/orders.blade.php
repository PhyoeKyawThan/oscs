@extends('admin.layouts.app')

@section('title', $customer->name . ' - Order History')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.show', $customer->id) }}">{{ $customer->name }}</a></li>
    <li class="breadcrumb-item active">Order History</li>
@endsection

@section('actions')
    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-primary">
        <i class="fas fa-user me-1"></i> Back to Profile
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">{{ $customer->name }} - Order History</h5>
            </div>
            <div class="col-md-6">
                <div class="text-muted text-end">
                    {{ $customer->orders()->count() }} total orders
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h5>No orders found</h5>
                <p class="text-muted">This customer hasn't placed any orders yet.</p>
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
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                {{ $order->created_at->format('M d, Y') }}
                                <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                {{ $order->items_count ?? 0 }} items
                            </td>
                            <td>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </td>
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
                                @php
                                    $paymentColors = [
                                        'Paid' => 'success',
                                        'Pending' => 'warning',
                                        'Failed' => 'danger',
                                        'Refunded' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                    {{ $order->payment_status ?? 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-sm btn-light" 
                                       data-bs-toggle="tooltip" 
                                       title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                       class="btn btn-sm btn-light" 
                                       data-bs-toggle="tooltip" 
                                       title="View Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
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
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Order Statistics -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Total Orders</h6>
                        <h3 class="fw-bold mb-0">{{ $customer->orders()->count() }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-shopping-bag"></i>
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
                        <h6 class="text-muted fw-normal">Total Spent</h6>
                        <h3 class="fw-bold mb-0">
                            ${{ number_format($customer->orders()->where('status', 'Completed')->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-dollar-sign"></i>
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
                        <h6 class="text-muted fw-normal">Avg. Order Value</h6>
                        @php
                            $totalOrders = $customer->orders()->where('status', 'Completed')->count();
                            $totalSpent = $customer->orders()->where('status', 'Completed')->sum('total_amount');
                            $avgValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
                        @endphp
                        <h3 class="fw-bold mb-0">${{ number_format($avgValue, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-chart-line"></i>
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
                        <h6 class="text-muted fw-normal">Last Order</h6>
                        @php
                            $lastOrder = $customer->orders()->latest()->first();
                        @endphp
                        <h3 class="fw-bold mb-0">
                            @if($lastOrder)
                                {{ \Carbon\Carbon::parse($lastOrder->created_at)->format('M d') }}
                            @else
                                N/A
                            @endif
                        </h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection