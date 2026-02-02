@extends('layouts.template')
@section('content')
    <!-- Order Details Page -->
    <div id="orderDetailsPage" class="page">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('customer.orders.index') }}" 
                   class="inline-flex items-center text-primary hover:text-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Orders
                </a>
            </div>

            <!-- Order Header -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">
                            Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <div class="inline-flex items-center px-4 py-2 rounded-lg {{ $order->getStatusColorAttribute() }}">
                            @php
                                $statusIcon = match($order->status) {
                                    'Pending' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'On Delivery' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'Completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'Cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                };
                            @endphp
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}" />
                            </svg>
                            <span class="font-medium">{{ $order->status }}</span>
                        </div>
                        
                        @if($order->status == 'Pending')
                        <button onclick="cancelOrder('{{ $order->order_number }}')"
                                class="ml-4 px-4 py-2 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            Cancel Order
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Items</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($order->items as $item)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start">
                                    <!-- Product Image (if available) -->
                                    <div class="flex-shrink-0 w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4">
                                        @if($item->product && $item->product->product_image)
                                        <img src="{{ asset('storage/' . $item->product->product_image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3 class="font-medium text-gray-900 dark:text-white">
                                                    {{ $item->product->name ?? 'Product Name' }}
                                                </h3>
                                                @if($item->product)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    Price: {{ number_format($item->product->price, 2) }} MMKS
                                                </p>
                                                @endif
                                            </div>
                                            
                                            <div class="text-right">
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    @if($item->product)
                                                    {{ number_format($item->product->price * $item->quantity, 2) }} MMKS
                                                    @else
                                                    N/A
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    Qty: {{ $item->quantity }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                No items found in this order.
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ number_format($order->total_amount, 2) }} MMKS
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                    <span class="font-medium text-gray-900 dark:text-white">Free</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                    <span class="font-medium text-gray-900 dark:text-white">0.00</span>
                                </div>
                                
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ number_format($order->total_amount, 2) }} MMKS
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Actions</h2>
                        <div class="flex flex-wrap gap-4">
                            <button onclick="reorderItems('{{ $order->order_number }}')"
                                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reorder All Items
                            </button>
                            
                            <a href="{{ route('customer.orders.invoice', $order->order_number) }}" 
                               target="_blank"
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Invoice
                            </a>
                            
                            <button onclick="trackOrder('{{ $order->order_number }}')"
                                    class="px-4 py-2 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Track Order
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Order Info Sidebar -->
                <div class="space-y-8">
                    <!-- Delivery Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Delivery Information</h2>
                        
                        <div class="space-y-3">
                            @if($deliveryInfo && is_array($deliveryInfo))
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $deliveryInfo['name'] ?? 'N/A' }}</p>
                            </div>
                            
                            @if(isset($deliveryInfo['phone']))
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $deliveryInfo['phone'] }}</p>
                            </div>
                            @endif
                            
                            @if(isset($deliveryInfo['address']))
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $deliveryInfo['address'] }}</p>
                            </div>
                            @endif
                            
                            @if(isset($deliveryInfo['city']) || isset($deliveryInfo['postal_code']))
                            <div class="grid grid-cols-2 gap-4">
                                @if(isset($deliveryInfo['city']))
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">City</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $deliveryInfo['city'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($deliveryInfo['postal_code']))
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Postal Code</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $deliveryInfo['postal_code'] }}</p>
                                </div>
                                @endif
                            </div>
                            @endif
                            @else
                            <p class="text-gray-500 dark:text-gray-400">No delivery information available.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Order Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Timeline</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900 dark:text-white">Order Placed</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($order->status == 'On Delivery')
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900 dark:text-white">Shipped</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->updated_at->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            @if($order->status == 'Completed')
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900 dark:text-white">Delivered</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->updated_at->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Need Help? -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Need Help?</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            If you have any questions about your order, please contact our support team.
                        </p>
                        <div class="space-y-3">
                            <a href="mailto:support@example.com"
                               class="flex items-center text-primary hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                support@example.com
                            </a>
                            <a href="tel:+1234567890"
                               class="flex items-center text-primary hover:text-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                +1 (234) 567-890
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Cancel order function
async function cancelOrder(orderNumber) {
    if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route("customer.orders.cancel", $order->order_number) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Order cancelled successfully.');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to cancel order.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error cancelling order.');
    }
}

// Reorder items
async function reorderItems(orderNumber) {
    try {
        const response = await fetch(`{{ route("customer.orders.reorder", $order->order_number) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Items added to cart successfully!');
            // Redirect to cart if specified
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            let message = data.message || 'Failed to reorder items.';
            if (data.unavailable_products && data.unavailable_products.length > 0) {
                message += '\n\nUnavailable products:\n' + data.unavailable_products.join('\n');
            }
            alert(message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error reordering items.');
    }
}

// Track order
async function trackOrder(orderNumber) {
    try {
        const response = await fetch(`{{ route("customer.orders.track", $order->order_number) }}`);
        const data = await response.json();
        
        if (data.success) {
            alert(`Order Status: ${data.order.status}\nEstimated Delivery: ${data.order.estimated_delivery}`);
        } else {
            alert('Error tracking order.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error tracking order.');
    }
}
</script>
@endpush