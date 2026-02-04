<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="d-flex align-items-center ms-auto">
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ \App\Models\Orders::where('status', 'Pending')->count() }}
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 300px;">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">Notifications</h6>
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        @php
                            $pendingOrders = \App\Models\Orders::where('status', 'Pending')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($pendingOrders as $order)
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="dropdown-item d-flex align-items-center py-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                                        <i class="fas fa-shopping-bag text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-medium">New Order #{{ $order->order_number }}</p>
                                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                <p class="mb-0">No new notifications</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-2 border-top">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary w-100">
                            View All Orders
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    @if(auth('admin')->user()->avatar)
                        <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                             class="avatar me-2" 
                             alt="{{ auth('admin')->user()->name }}">
                    @else
                        <div class="avatar bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <span class="d-none d-md-inline">{{ auth('admin')->user()->name }}</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                            @csrf
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ config('app.name') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('admin.layouts.sidebar')
    </div>
</div>