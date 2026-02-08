@extends('admin.layouts.app')

@section('title', 'Orders Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('actions')
    <a href="{{ route('admin.orders.index') . '?export=csv' }}" class="btn btn-success">
        <i class="fas fa-download me-1"></i> Export CSV
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">All Orders</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..."
                            value="{{ request('search') }}">

                        <select name="status" class="form-select" style="width: 150px;">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
                            <i class="fas fa-redo"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h5>No orders found</h5>
                    <p class="text-muted">When customers place orders, they'll appear here.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
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
                                        @if($order->user)
                                            <div>
                                                <strong>{{ $order->user->name }}</strong>
                                                <div class="text-muted small">{{ $order->user->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Guest</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('M d, Y') }}
                                        <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        {{ count($order->items) ?? 0 }} items
                                    </td>
                                    <td>
                                        <strong>{{ number_format($order->total_amount, 2) }} MMKS</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Pending' => 'warning',
                                                'Confirmed' => 'info',
                                                'Shipping' => 'primary',
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
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light"
                                                data-bs-toggle="tooltip" title="View Order">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.orders.invoice', $order->id) }}">
                                                            <i class="fas fa-file-invoice me-2"></i> Invoice
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.orders.invoice', $order->id) }}?download=1">
                                                            <i class="fas fa-download me-2"></i> Download PDF
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    @if($order->status == 'Cancelled')
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                                                id="delete-order-{{ $order->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="confirmDelete(event, 'delete-order-{{ $order->id }}')">
                                                                    <i class="fas fa-trash me-2"></i> Delete
                                                                </a>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
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
@endsection