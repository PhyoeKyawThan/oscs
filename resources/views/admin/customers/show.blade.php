@extends('admin.layouts.app')

@section('title', 'Customer Profile - ' . $customer->name)
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800">Customers</a>
    </li>
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Customer Profile</span>
    </li>
@endsection

@section('actions')
    <div class="flex rounded-lg overflow-hidden border border-gray-200">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="{{ route('admin.customers.orders', $customer->id) }}" 
           class="inline-flex items-center px-4 py-2 border-l border-blue-500 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium transition-colors duration-200">
            <i class="fas fa-shopping-bag"></i>
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Customer Profile Column -->
    <div class="lg:col-span-4">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6 text-center">
                @if($customer->avatar)
                    <img src="{{ asset('storage/' . $customer->avatar) }}" 
                         alt="{{ $customer->name }}" 
                         class="rounded-full mx-auto w-36 h-36 object-cover border-4 border-white shadow-md">
                @else
                    <div class="bg-blue-50 rounded-full w-36 h-36 mx-auto flex items-center justify-center border-4 border-white shadow-md">
                        <i class="fas fa-user fa-4x text-blue-600"></i>
                    </div>
                @endif
                <h5 class="text-xl font-semibold text-gray-900 my-3">{{ $customer->name }}</h5>
                <p class="text-gray-500 text-sm mb-2">{{ $customer->email }}</p>
                <p class="mb-4">
                    @if($customer->email_verified_at)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Verified</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Unverified</span>
                    @endif
                </p>
                
                <div class="flex justify-center gap-2">
                    @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-phone mr-2"></i> Call
                        </a>
                    @endif
                    <a href="mailto:{{ $customer->email }}" 
                       class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Customer Statistics</h6>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <h4 class="text-xl font-bold text-gray-900">{{ $stats['total_orders'] }}</h4>
                        <p class="text-xs text-gray-500">Total Orders</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <h4 class="text-xl font-bold text-gray-900">${{ number_format($stats['total_spent'], 2) }}</h4>
                        <p class="text-xs text-gray-500">Total Spent</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <h4 class="text-xl font-bold text-gray-900">${{ number_format($stats['avg_order_value'], 2) }}</h4>
                        <p class="text-xs text-gray-500">Avg. Order Value</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <h4 class="text-xl font-bold text-gray-900">
                            @if($stats['last_order_date'])
                                {{ \Carbon\Carbon::parse($stats['last_order_date'])->format('M d') }}
                            @else
                                N/A
                            @endif
                        </h4>
                        <p class="text-xs text-gray-500">Last Order</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h6 class="font-semibold text-gray-900">Contact Information</h6>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</span>
                    <p class="text-sm text-gray-900">{{ $customer->email }}</p>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</span>
                    <p class="text-sm text-gray-900">{{ $customer->phone ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address</span>
                    <p class="text-sm text-gray-900">{{ $customer->address ?? 'Not provided' }}</p>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Member Since</span>
                    <p class="text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</span>
                    <p class="text-sm text-gray-900">
                        @if($customer->last_login)
                            {{ \Carbon\Carbon::parse($customer->last_login)->format('M d, Y h:i A') }}
                        @else
                            <span class="text-gray-500">Never logged in</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Orders Column -->
    <div class="lg:col-span-8">
        <!-- Recent Orders Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h5 class="font-semibold text-gray-900">Recent Orders</h5>
                <a href="{{ route('admin.customers.orders', $customer->id) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                    View All Orders
                </a>
            </div>
            <div class="p-4">
                @if($customer->orders->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-bag fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-base font-semibold text-gray-700">No orders yet</h5>
                        <p class="text-sm text-gray-500">This customer hasn't placed any orders.</p>
                    </div>
                @else
                    <div class="-mx-4 overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Order #</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Items</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->orders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $order->items_count ?? $order->items->count() }} items
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
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
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Activity Timeline Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h5 class="font-semibold text-gray-900">Recent Activity</h5>
            </div>
            <div class="p-4">
                <div class="timeline">
                    <!-- Account Created -->
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-green-500"></div>
                        <div class="timeline-content pl-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1">
                                <h6 class="text-sm font-semibold text-gray-900">Account Created</h6>
                                <small class="text-gray-500 text-xs">{{ $customer->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="text-sm text-gray-600">Customer registered on the website</p>
                        </div>
                    </div>
                    
                    @if($customer->email_verified_at)
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-cyan-500"></div>
                        <div class="timeline-content pl-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1">
                                <h6 class="text-sm font-semibold text-gray-900">Email Verified</h6>
                                <small class="text-gray-500 text-xs">{{ $customer->email_verified_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="text-sm text-gray-600">Customer verified their email address</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($customer->last_login)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-blue-500"></div>
                        <div class="timeline-content pl-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1">
                                <h6 class="text-sm font-semibold text-gray-900">Last Login</h6>
                                <small class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($customer->last_login)->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="text-sm text-gray-600">Customer logged into their account</p>
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
    padding-left: 24px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
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
    left: -20px;
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