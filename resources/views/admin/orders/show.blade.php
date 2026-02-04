@extends('admin.layouts.app')

@section('title', 'Order Details - #' . $order->order_number)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order Details</li>
@endsection

@section('actions')
    <div class="btn-group">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-invoice me-1"></i> View Invoice
        </a>
        <a href="{{ route('admin.orders.invoice', $order->id) }}?download=1" class="btn btn-outline-success">
            <i class="fas fa-download"></i>
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Order Summary -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width: auto;">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Items -->
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="rounded me-3" 
                                                 width="50" 
                                                 height="50" 
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name ?? 'Product Deleted' }}</h6>
                                            <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Totals -->
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Customer Notes -->
                        @if($order->notes)
                        <div class="card border">
                            <div class="card-body">
                                <h6>Customer Notes</h6>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="mb-3">Order Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                                </div>
                                @php
                                    $deliveryInfo = is_array($order->delivery_information) 
                                        ? $order->delivery_information 
                                        : json_decode($order->delivery_information, true);
                                    $shipping = $deliveryInfo['method'] ?? '';
                                    $shippingCost = 0;
                                    if ($shipping == 'express') {
                                        $shippingCost = 12.99;
                                    } elseif ($shipping == 'standard') {
                                        $shippingCost = 5.99;
                                    }
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping</span>
                                    <span>${{ number_format($shippingCost, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span>${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status History -->
        @if($order->status_history && is_array($order->status_history))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status History</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach(array_reverse($order->status_history) as $history)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">{{ $history['status'] }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($history['changed_at'])->format('M d, Y h:i A') }}</small>
                            </div>
                            @if($history['notes'])
                                <p class="mb-0 mt-1">{{ $history['notes'] }}</p>
                            @endif
                            <small class="text-muted">Changed by: {{ $history['changed_by'] ?? 'System' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Order Information -->
    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                @if($order->user)
                <div class="d-flex align-items-center mb-3">
                    @if($order->user->avatar)
                        <img src="{{ asset('storage/' . $order->user->avatar) }}" 
                             alt="{{ $order->user->name }}" 
                             class="rounded-circle me-3" 
                             width="50" 
                             height="50" 
                             style="object-fit: cover;">
                    @else
                        <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </div>
                </div>
                @endif
                
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <p class="mb-0">{{ $deliveryInfo['email'] ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone</label>
                        <p class="mb-0">{{ $deliveryInfo['phone'] ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Shipping Address</label>
                        <address class="mb-0">
                            {{ $deliveryInfo['address'] ?? '' }}<br>
                            {{ $deliveryInfo['city'] ?? '' }}, {{ $deliveryInfo['state'] ?? '' }}<br>
                            {{ $deliveryInfo['postal_code'] ?? '' }}
                        </address>
                    </div>
                </div>
                
                @if($order->user)
                <div class="mt-3">
                    <a href="{{ route('admin.customers.show', $order->user_id) }}" 
                       class="btn btn-sm btn-outline-primary w-100">
                        View Customer Profile
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Order Date</label>
                        <p class="mb-0">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Last Updated</label>
                        <p class="mb-0">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Payment Method</label>
                        <p class="mb-0">{{ $order->payment_method ?? 'COD' }}</p>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Payment Status</label>
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
                    </div>
                    <div class="col-6">
                        <label class="form-label">Shipping Method</label>
                        <p class="mb-0">{{ ucfirst($deliveryInfo['method'] ?? 'Standard') }}</p>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Order Status</label>
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
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delivery Instructions -->
        @if(isset($deliveryInfo['instructions']) && $deliveryInfo['instructions'])
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Delivery Instructions</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $deliveryInfo['instructions'] }}</p>
            </div>
        </div>
        @endif
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