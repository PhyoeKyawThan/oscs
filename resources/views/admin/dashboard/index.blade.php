@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Total Orders</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                This month
                            </small>
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
                            <h6 class="text-muted fw-normal">Total Revenue</h6>
                            <h3 class="fw-bold mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                {{ $stats['total_revenue'] > 0 ? '10.5%' : '0%' }}
                            </small>
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
                            <h6 class="text-muted fw-normal">Total Products</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_products'] }}</h3>
                            <small class="text-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $stats['low_stock_products'] }} low stock
                            </small>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-box"></i>
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
                            <h6 class="text-muted fw-normal">Total Customers</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_customers'] }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                New this month
                            </small>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Revenue Overview (Last 30 Days)</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Last 30 Days
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Order Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="250"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending</span>
                            <span class="fw-bold">{{ $stats['pending_orders'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Processing</span>
                            <span class="fw-bold">{{ \App\Models\Orders::where('status', 'Processing')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Completed</span>
                            <span class="fw-bold">{{ \App\Models\Orders::where('status', 'Completed')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Cancelled</span>
                            <span class="fw-bold">{{ \App\Models\Orders::where('status', 'Cancelled')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                {{ $order->user->name }}
                                            @else
                                                Guest
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }} MMKS</td>
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
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        {{-- <div class="col-xl-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @foreach($topProducts as $product)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-light rounded p-2">
                                <i class="fas fa-box text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <small class="text-muted">{{ $product->total_sold }} sold</small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="fw-bold">{{ $product->price ?? 'N/A' }}MMKS</span>
                        </div>
                    </div>
                    @endforeach

                    @if($topProducts->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                        <p class="mb-0">No sales data available</p>
                    </div>
                    @endif
                </div>
            </div> --}}

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0">{{ $stats['pending_orders'] }}</h4>
                                <small class="text-muted">Pending Orders</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0">{{ $stats['low_stock_products'] }}</h4>
                                <small class="text-muted">Low Stock</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0">{{ $stats['total_categories'] }}</h4>
                                <small class="text-muted">Categories</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                @php
                                    $avgOrderValue = $stats['total_revenue'] / max($stats['total_orders'], 1);
                                @endphp
                                <h4 class="mb-0">${{ number_format($avgOrderValue, 2) }}</h4>
                                <small class="text-muted">Avg. Order Value</small>
                            </div>
                        </div>
                    </div>
                </div>
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