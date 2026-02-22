@extends('admin.layouts.app')

@section('title', $customer->name . ' - Order History')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800">Customers</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-800">{{ $customer->name }}</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Order History</span>
    </li>
@endsection

@section('actions')
    <a href="{{ route('admin.customers.show', $customer->id) }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        <i class="fas fa-user mr-2"></i> Back to Profile
    </a>
@endsection

@section('content')
<!-- Main Card -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <!-- Header -->
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h5 class="text-lg font-semibold text-gray-900">{{ $customer->name }} - Order History</h5>
            <div class="text-sm text-gray-500">
                {{ $customer->orders()->count() }} total orders
            </div>
        </div>
    </div>
    
    <!-- Body -->
    <div class="p-4 sm:p-6">
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-shopping-bag fa-4x text-gray-300 mb-4"></i>
                <h5 class="text-xl font-semibold text-gray-700 mb-2">No orders found</h5>
                <p class="text-gray-500">This customer hasn't placed any orders yet.</p>
            </div>
        @else
            <!-- Table Container with Horizontal Scroll -->
            <div class="-mx-4 sm:-mx-6 overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Order #</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Items</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Payment</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900 text-sm">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order->items_count ?? $order->items->count() }} items
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900 text-sm">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
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
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentColors = [
                                            'Paid' => 'bg-green-100 text-green-800',
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Failed' => 'bg-red-100 text-red-800',
                                            'Refunded' => 'bg-gray-100 text-gray-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->payment_status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                           data-toggle="tooltip" 
                                           title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                                           class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                           data-toggle="tooltip" 
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
            </div>
            
            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                <div class="text-sm text-gray-500">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </div>
                <div class="flex justify-center">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Order Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $customer->orders()->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Spent -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Spent</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        ${{ number_format($customer->orders()->where('status', 'Completed')->sum('total_amount'), 2) }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Average Order Value -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Avg. Order Value</p>
                    @php
                        $totalOrders = $customer->orders()->where('status', 'Completed')->count();
                        $totalSpent = $customer->orders()->where('status', 'Completed')->sum('total_amount');
                        $avgValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
                    @endphp
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($avgValue, 2) }}</h3>
                </div>
                <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl text-cyan-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Last Order -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Last Order</p>
                    @php
                        $lastOrder = $customer->orders()->latest()->first();
                    @endphp
                    <h3 class="text-2xl font-bold text-gray-900">
                        @if($lastOrder)
                            {{ \Carbon\Carbon::parse($lastOrder->created_at)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-xl text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics Row (Optional) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Orders by Status -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h6 class="font-semibold text-gray-900">Orders by Status</h6>
        </div>
        <div class="p-5">
            <div class="space-y-3">
                @php
                    $statusCounts = [
                        'Completed' => $customer->orders()->where('status', 'Completed')->count(),
                        'Processing' => $customer->orders()->where('status', 'Processing')->count(),
                        'Shipped' => $customer->orders()->where('status', 'Shipped')->count(),
                        'Cancelled' => $customer->orders()->where('status', 'Cancelled')->count(),
                    ];
                @endphp
                @foreach($statusCounts as $status => $count)
                    @if($count > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $status }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h6 class="font-semibold text-gray-900">Recent Activity</h6>
        </div>
        <div class="p-5">
            @if($customer->orders()->count() > 0)
                <div class="space-y-3">
                    @foreach($customer->orders()->latest()->take(3)->get() as $recentOrder)
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium text-gray-900">#{{ $recentOrder->order_number }}</span>
                                <span class="text-xs text-gray-500 ml-2">{{ $recentOrder->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($recentOrder->status == 'Completed') bg-green-100 text-green-800
                                @elseif($recentOrder->status == 'Processing') bg-blue-100 text-blue-800
                                @elseif($recentOrder->status == 'Cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $recentOrder->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-2">No recent activity</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if needed
    const tooltipTriggers = document.querySelectorAll('[data-toggle="tooltip"]');
    // You can implement tooltips here if needed
});
</script>
@endpush