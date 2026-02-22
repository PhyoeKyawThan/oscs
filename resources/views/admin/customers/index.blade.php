@extends('admin.layouts.app')

@section('title', 'Customers Management')
@section('breadcrumb')
    <li class="inline-flex items-center text-gray-600 before:content-['/'] before:mx-2">
        <span>Customers</span>
    </li>
@endsection

{{-- @section('actions')
    <a href="{{ route('admin.customers.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i> Add Customer
    </a>
@endsection --}}

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <!-- Header -->
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h5 class="text-lg font-semibold text-gray-900">All Customers</h5>
            
            <form method="GET" class="flex w-full sm:w-auto">
                <input type="text" 
                       name="search" 
                       class="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" 
                       placeholder="Search customers..." 
                       value="{{ request('search') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors duration-200">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.customers.index') }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg ml-2 transition-colors duration-200">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>
    </div>
    
    <!-- Body -->
    <div class="p-4 sm:p-6">
        @if($customers->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-users fa-4x text-gray-300 mb-4"></i>
                <h5 class="text-xl font-semibold text-gray-700 mb-2">No customers found</h5>
                <p class="text-gray-500 mb-4">When customers register, they'll appear here.</p>
                <a href="{{ route('admin.customers.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Add Customer
                </a>
            </div>
        @else
            <!-- Table Container with Horizontal Scroll -->
            <div class="-mx-4 sm:-mx-6 overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Phone</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Orders</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Total Spent</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Registered</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center">
                                        @if($customer->avatar)
                                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                                 alt="{{ $customer->name }}" 
                                                 class="rounded-full mr-3 w-10 h-10 object-cover">
                                        @else
                                            <div class="bg-blue-50 rounded-full mr-3 flex items-center justify-center w-10 h-10">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-[150px]">
                                            <h6 class="text-sm font-medium text-gray-900">{{ $customer->name }}</h6>
                                            <small class="text-xs text-gray-500">ID: {{ $customer->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:{{ $customer->email }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $customer->email }}
                                    </a>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $customer->phone ?? 'N/A' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        {{ $customer->orders_count }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $totalSpent = \App\Models\Orders::where('user_id', $customer->id)
                                            ->where('status', 'Completed')
                                            ->sum('total_amount');
                                    @endphp
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($totalSpent, 2) }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $customer->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @if($customer->email_verified_at)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Verified</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Unverified</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                           class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                                           data-toggle="tooltip" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.customers.destroy', $customer->id) }}" 
                                              id="delete-customer-{{ $customer->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="p-2 bg-gray-100 hover:bg-gray-200 text-red-600 rounded-lg transition-colors duration-200"
                                                    data-toggle="tooltip" 
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
            </div>
            
            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                <div class="text-sm text-gray-500">
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                </div>
                <div class="flex justify-center">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <!-- Total Customers -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Customers</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $customers->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Today (Commented out but kept for reference) 
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Active Today</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                       
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>
    -->
    
    <!-- New This Month -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">New This Month</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-xl text-cyan-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Verified Users -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Verified Users</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ \App\Models\User::whereNotNull('email_verified_at')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-xl text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- You can add a 4th card here or uncomment the active today one -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Avg. Orders/User</p>
                    @php
                        $totalUsers = $customers->total() ?: 1;
                        $totalOrders = \App\Models\Orders::count();
                        $avgOrders = round($totalOrders / $totalUsers, 1);
                    @endphp
                    <h3 class="text-2xl font-bold text-gray-900">{{ $avgOrders }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Confirm delete function
function confirmDelete(event, formId) {
    event.preventDefault();
    if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
        document.getElementById(formId).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if needed
    const tooltipTriggers = document.querySelectorAll('[data-toggle="tooltip"]');
    // You can implement tooltips here if needed
});
</script>
@endpush