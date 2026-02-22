@extends('admin.layouts.app')

@section('title', 'Orders Management')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Orders</span>
    </li>
@endsection

@section('actions')
    <a href="{{ route('admin.orders.index') . '?export=csv' }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        <i class="fas fa-download mr-2"></i> Export CSV
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                <div>
                    <h5 class="text-lg font-semibold text-gray-900">All Orders</h5>
                </div>
                <div>
                    <form method="GET" class="flex flex-wrap gap-2">
                        <input type="text" 
                               name="search" 
                               class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Search orders..."
                               value="{{ request('search') }}">

                        <select name="status" 
                                class="w-36 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <a href="{{ route('admin.orders.index') }}" 
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                            <i class="fas fa-redo"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            @if($orders->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-shopping-bag fa-4x text-gray-300 mb-4"></i>
                    <h5 class="text-xl font-semibold text-gray-700 mb-2">No orders found</h5>
                    <p class="text-gray-500">When customers place orders, they'll appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->user)
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">Guest</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ count($order->items) ?? 0 }} items
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-900">{{ number_format($order->total_amount, 2) }} MMKS</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                                'Confirmed' => 'bg-blue-100 text-blue-800',
                                                'Processing' => 'bg-blue-100 text-blue-800',
                                                'Shipped' => 'bg-indigo-100 text-indigo-800',
                                                'Delivered' => 'bg-green-100 text-green-800',
                                                'Completed' => 'bg-green-100 text-green-800',
                                                'Cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $paymentColors = [
                                                'Paid' => 'bg-green-100 text-green-800',
                                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                                'Failed' => 'bg-red-100 text-red-800',
                                                'Refunded' => 'bg-gray-100 text-gray-800'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $order->payment_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                               data-toggle="tooltip" 
                                               title="View Order">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-file-invoice mr-2"></i> Invoice
                                                    </a>
                                                    <a href="{{ route('admin.orders.invoice', $order->id) }}?download=1" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-download mr-2"></i> Download PDF
                                                    </a>
                                                    
                                                    @if($order->status == 'Cancelled')
                                                        <div class="border-t border-gray-100"></div>
                                                        <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                                              id="delete-order-{{ $order->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" 
                                                               onclick="confirmDelete(event, 'delete-order-{{ $order->id }}')"
                                                               class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                <i class="fas fa-trash mr-2"></i> Delete
                                                            </a>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                    <div class="text-sm text-gray-500">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize tooltips if needed
        document.addEventListener('DOMContentLoaded', function() {
            // You can implement tooltips here if needed
        });
    </script>
    @endpush
@endsection