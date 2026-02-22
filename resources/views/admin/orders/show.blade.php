@extends('admin.layouts.app')

@section('title', 'Order Details - #' . $order->order_number)
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">Orders</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Order Details</span>
    </li>
@endsection

@section('actions')
    <div class="flex rounded-lg overflow-hidden border border-gray-200">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition-colors duration-200">
            <i class="fas fa-file-invoice mr-1"></i> View Invoice
        </a>
        <a href="{{ route('admin.orders.invoice', $order->id) }}?download=1"
           class="inline-flex items-center px-4 py-2 border-l border-green-500 bg-green-50 hover:bg-green-100 text-green-700 text-sm font-medium transition-colors duration-200">
            <i class="fas fa-download"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Order Summary Column -->
        <div class="lg:col-span-8">
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h5 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h5>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}"
                                  class="flex gap-2">
                                @csrf
                                <select name="status" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Confirmed" {{ $order->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Shipping" {{ $order->status == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                                    <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Order Items -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded-lg mr-3 w-12 h-12 object-cover">
                                                @else
                                                    <div class="bg-gray-100 rounded-lg mr-3 flex items-center justify-center w-12 h-12">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Product Deleted' }}</h6>
                                                    <small class="text-gray-500 text-xs">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->product->price, 2) }} MMKS</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ number_format($item->product->price * $item->quantity, 2) }} MMKS</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Totals and Payment Proof -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Customer Notes -->
                        <div>
                            @if($order->notes)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h6 class="text-sm font-semibold text-gray-900 mb-2">Customer Notes</h6>
                                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h6 class="text-sm font-semibold text-gray-900 mb-3">Order Summary</h6>
                            @php
                                $deliveryInfo = is_array($order->delivery_information)
                                    ? $order->delivery_information
                                    : json_decode($order->delivery_information, true);
                                $shipping = $deliveryInfo['method'] ?? '';
                                $shippingCost = 0;
                                if ($shipping == 'express') {
                                    $shippingCost = 4000.00;
                                } elseif ($shipping == 'standard') {
                                    $shippingCost = 3000.00;
                                }
                            @endphp
                            <div class="flex justify-between mb-2 text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-900">{{ number_format($shippingCost, 2) }} MMKS</span>
                            </div>
                            <hr class="my-3 border-gray-200">
                            <div class="flex justify-between font-semibold text-base">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">{{ number_format($order->total_amount + $shippingCost, 2) }} MMKS</span>
                            </div>
                        </div>
                        
                        <!-- Payment Proof -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Payment Proof</h4>
                            <img class="w-full rounded-lg" src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof">
                            
                            <!-- Payment Status Update -->
                            <form method="POST" action="{{ route('admin.orders.update-payment-status', $order->id) }}"
                                  class="flex gap-2 mt-4 justify-end">
                                @csrf
                                <select name="payment_status" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                    <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="Failed" {{ $order->payment_status == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="Refunded" {{ $order->payment_status == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            @if($order->status_history && is_array($order->status_history))
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900">Status History</h5>
                    </div>
                    <div class="p-6">
                        <div class="timeline">
                            @foreach(array_reverse($order->status_history) as $history)
                                <div class="timeline-item mb-4">
                                    <div class="timeline-marker bg-blue-600"></div>
                                    <div class="timeline-content pl-8">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1">
                                            <h6 class="text-sm font-semibold text-gray-900">{{ $history['status'] }}</h6>
                                            <small class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($history['changed_at'])->format('M d, Y h:i A') }}</small>
                                        </div>
                                        @if($history['notes'])
                                            <p class="text-sm text-gray-600 mb-1">{{ $history['notes'] }}</p>
                                        @endif
                                        <small class="text-gray-400 text-xs">Changed by: {{ $history['changed_by'] ?? 'System' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Information Column -->
        <div class="lg:col-span-4">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900">Customer Information</h5>
                </div>
                <div class="p-6">
                    @if($order->user)
                        <div class="flex items-center mb-4">
                            @if($order->user->avatar)
                                <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="{{ $order->user->name }}"
                                     class="rounded-full mr-3 w-12 h-12 object-cover">
                            @else
                                <div class="bg-blue-50 rounded-full mr-3 flex items-center justify-center w-12 h-12">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="text-sm font-semibold text-gray-900">{{ $order->user->name }}</h6>
                                <small class="text-gray-500 text-xs">{{ $order->user->email }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                            <p class="text-sm text-gray-900">{{ $deliveryInfo['email'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</label>
                            <p class="text-sm text-gray-900">{{ $deliveryInfo['phone'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Address</label>
                            <address class="text-sm text-gray-900 not-italic">
                                {{ $deliveryInfo['address'] ?? '' }}<br>
                                {{ $deliveryInfo['city'] ?? '' }}, {{ $deliveryInfo['state'] ?? '' }}<br>
                                {{ $deliveryInfo['postal_code'] ?? '' }}
                            </address>
                        </div>
                    </div>

                    @if($order->user)
                        <div class="mt-4">
                            <a href="{{ route('admin.customers.show', $order->user_id) }}"
                               class="block w-full text-center px-4 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium rounded-lg transition-colors duration-200">
                                View Customer Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900">Order Information</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</label>
                            <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</label>
                            <p class="text-sm text-gray-900">{{ $order->payment_method ?? 'COD' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</label>
                            @php
                                $paymentColors = [
                                    'Paid' => 'bg-green-100 text-green-800',
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Failed' => 'bg-red-100 text-red-800',
                                    'Refunded' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $order->payment_status ?? 'Pending' }}
                            </span>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Method</label>
                            <p class="text-sm text-gray-900">{{ ucfirst($deliveryInfo['method'] ?? 'Standard') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Order Status</label>
                            @php
                                $statusColors = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Processing' => 'bg-blue-100 text-blue-800',
                                    'Shipped' => 'bg-indigo-100 text-indigo-800',
                                    'Delivered' => 'bg-green-100 text-green-800',
                                    'Completed' => 'bg-green-100 text-green-800',
                                    'Cancelled' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Instructions -->
            @if(isset($deliveryInfo['instructions']) && $deliveryInfo['instructions'])
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900">Delivery Instructions</h5>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600">{{ $deliveryInfo['instructions'] }}</p>
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
            padding-left: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e5e7eb;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-marker {
            position: absolute;
            left: -17px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            z-index: 1;
        }

        .timeline-content {
            padding-bottom: 16px;
        }
    </style>
@endpush