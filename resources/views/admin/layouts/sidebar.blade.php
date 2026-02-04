<aside class="sidebar text-white">
    <div class="sidebar-header p-4 border-bottom border-white border-opacity-25">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                <div class="d-flex align-items-center">
                    <i class="fas fa-store fa-2x me-3"></i>
                    <div>
                        <h4 class="fw-bold mb-0">{{ config('app.name') }}</h4>
                        <small class="text-white text-opacity-75">Admin Panel</small>
                    </div>
                </div>
            </a>
            <button class="toggle-sidebar d-none d-lg-block" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <div class="sidebar-content p-3">
        <div class="mb-4">
            <small class="text-white text-opacity-50 fw-bold text-uppercase">Main</small>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mb-4">
            <small class="text-white text-opacity-50 fw-bold text-uppercase">Management</small>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-shopping-bag me-3"></i>
                        <span class="sidebar-text">Orders</span>
                        @php
                            $pendingOrders = \App\Models\Orders::where('status', 'Pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="badge bg-danger float-end">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.products.*') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-box me-3"></i>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.categories.*') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-tags me-3"></i>
                        <span class="sidebar-text">Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.customers.*') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-users me-3"></i>
                        <span class="sidebar-text">Customers</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mb-4">
            <small class="text-white text-opacity-50 fw-bold text-uppercase">System</small>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.general') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.settings.*') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-cog me-3"></i>
                        <span class="sidebar-text">Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.profile') }}" class="nav-link text-white py-3 rounded {{ request()->routeIs('admin.profile') ? 'bg-white bg-opacity-25' : '' }}">
                        <i class="fas fa-user me-3"></i>
                        <span class="sidebar-text">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mt-auto p-3 border-top border-white border-opacity-25">
            <div class="d-flex align-items-center">
                @if(auth('admin')->user()->avatar)
                    <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                         class="avatar me-3" 
                         alt="{{ auth('admin')->user()->name }}">
                @else
                    <div class="avatar bg-white bg-opacity-25 d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                @endif
                <div>
                    <h6 class="mb-0">{{ auth('admin')->user()->name }}</h6>
                    <small class="text-white text-opacity-75">{{ auth('admin')->user()->role }}</small>
                </div>
            </div>
        </div>
    </div>
</aside>