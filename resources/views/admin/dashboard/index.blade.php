@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

    <!-- Total Orders -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-between">
            <div>
                <h6 class="text-gray-500 text-sm">Total Orders</h6>
                <h3 class="text-2xl font-bold">{{ $stats['total_orders'] }}</h3>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> This month
                </p>
            </div>
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-between">
            <div>
                <h6 class="text-gray-500 text-sm">Total Revenue</h6>
                <h3 class="text-2xl font-bold">
                    ${{ number_format($stats['total_revenue'], 2) }}
                </h3>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $stats['total_revenue'] > 0 ? '10.5%' : '0%' }}
                </p>
            </div>
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-between">
            <div>
                <h6 class="text-gray-500 text-sm">Total Products</h6>
                <h3 class="text-2xl font-bold">{{ $stats['total_products'] }}</h3>
                <p class="text-red-600 text-sm mt-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $stats['low_stock_products'] }} low stock
                </p>
            </div>
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-between">
            <div>
                <h6 class="text-gray-500 text-sm">Total Customers</h6>
                <h3 class="text-2xl font-bold">{{ $stats['total_customers'] }}</h3>
                <p class="text-green-600 text-sm mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> New this month
                </p>
            </div>
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

</div>


<!-- Charts Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- Revenue Chart -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow">
        <div class="flex justify-between items-center p-6 border-b">
            <h6 class="font-semibold">Revenue Overview (Last 30 Days)</h6>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="250"></canvas>
        </div>
    </div>

    <!-- Order Status -->
    <div class="bg-white rounded-2xl shadow">
        <div class="p-6 border-b">
            <h6 class="font-semibold">Order Status</h6>
        </div>
        <div class="p-6">
            <canvas id="orderStatusChart" height="250"></canvas>

            <div class="mt-4 space-y-2">
                <div class="flex justify-between">
                    <span>Pending</span>
                    <span class="font-bold">{{ $stats['pending_orders'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Processing</span>
                    <span class="font-bold">{{ \App\Models\Orders::where('status', 'Processing')->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Completed</span>
                    <span class="font-bold">{{ \App\Models\Orders::where('status', 'Completed')->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Cancelled</span>
                    <span class="font-bold">{{ \App\Models\Orders::where('status', 'Cancelled')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Recent Orders -->
<div class="bg-white rounded-2xl shadow mb-6">
    <div class="flex justify-between items-center p-6 border-b">
        <h6 class="font-semibold">Recent Orders</h6>
        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
            View All
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">

                @foreach($recentOrders as $order)
                @php
                    $statusColors = [
                        'Pending' => 'bg-yellow-100 text-yellow-700',
                        'Processing' => 'bg-cyan-100 text-cyan-700',
                        'Shipped' => 'bg-blue-100 text-blue-700',
                        'Delivered' => 'bg-green-100 text-green-700',
                        'Completed' => 'bg-green-100 text-green-700',
                        'Cancelled' => 'bg-red-100 text-red-700'
                    ];
                @endphp

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold">
                        <a href="{{ route('admin.orders.show', $order->id) }}">
                            #{{ $order->order_number }}
                        </a>
                    </td>

                    <td class="px-6 py-4">
                        {{ $order->user->name ?? 'Guest' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>

                    <td class="px-6 py-4">
                        {{ number_format($order->total_amount, 2) }} MMKS
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $order->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
</div>


<!-- Quick Stats -->
<div class="bg-white rounded-2xl shadow p-6">
    <h6 class="font-semibold mb-4">Quick Stats</h6>

    <div class="grid grid-cols-2 gap-4 text-center">
        <div class="p-4 bg-gray-100 rounded-xl">
            <h4 class="text-xl font-bold">{{ $stats['pending_orders'] }}</h4>
            <p class="text-gray-500 text-sm">Pending Orders</p>
        </div>

        <div class="p-4 bg-gray-100 rounded-xl">
            <h4 class="text-xl font-bold">{{ $stats['low_stock_products'] }}</h4>
            <p class="text-gray-500 text-sm">Low Stock</p>
        </div>

        <div class="p-4 bg-gray-100 rounded-xl">
            <h4 class="text-xl font-bold">{{ $stats['total_categories'] }}</h4>
            <p class="text-gray-500 text-sm">Categories</p>
        </div>

        @php
            $avgOrderValue = $stats['total_revenue'] / max($stats['total_orders'], 1);
        @endphp

        <div class="p-4 bg-gray-100 rounded-xl">
            <h4 class="text-xl font-bold">${{ number_format($avgOrderValue, 2) }}</h4>
            <p class="text-gray-500 text-sm">Avg. Order Value</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueDates = @json($salesData->pluck('date'));
            const revenueAmounts = @json($salesData->pluck('revenue'));

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueDates,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueAmounts,
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y.toFixed(2) + 'MMKS';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value + ' MMKS';
                                }
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusData = {
                labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                    {{ $stats['pending_orders'] }},
                    {{ \App\Models\Orders::where('status', 'Processing')->count() }},
                    {{ \App\Models\Orders::where('status', 'Completed')->count() }},
                        {{ \App\Models\Orders::where('status', 'Cancelled')->count() }}
                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#0dcaf0',
                        '#198754',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            };

            new Chart(statusCtx, {
                type: 'doughnut',
                data: orderStatusData,
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endpush