<aside class="sidebar text-white h-screen fixed top-0 left-0 z-[1000] transition-all duration-300" 
       :class="{ 'collapsed': sidebarCollapsed }" 
       x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }">
    
    <!-- Sidebar Header -->
    <div class="px-4 py-5 border-b border-white/25">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white no-underline">
                <div class="flex items-center">
                    <i class="fas fa-store fa-2x mr-3"></i>
                    <div x-show="!sidebarCollapsed" x-transition>
                        <h4 class="font-bold text-xl mb-0">{{ config('app.name') }}</h4>
                        <small class="text-white/75">Admin Panel</small>
                    </div>
                </div>
            </a>
            <button class="toggle-sidebar hidden lg:block bg-transparent border-0 text-white text-xl cursor-pointer" 
                    @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <!-- Sidebar Content -->
    <div class="p-3 h-[calc(100vh-80px)] overflow-y-auto">
        <!-- Main Menu -->
        <div class="mb-6">
            <small class="text-white/50 font-bold uppercase tracking-wider block px-3 mb-2" 
                   x-show="!sidebarCollapsed">Main</small>
            <ul class="space-y-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Management Menu -->
        <div class="mb-6">
            <small class="text-white/50 font-bold uppercase tracking-wider block px-3 mb-2" 
                   x-show="!sidebarCollapsed">Management</small>
            <ul class="space-y-1">
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-shopping-bag w-6"></i>
                        <span class="sidebar-text ml-3 flex-1" x-show="!sidebarCollapsed">Orders</span>
                        @php
                            $pendingOrders = \App\Models\Orders::where('status', 'Pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="bg-red-600 text-white text-xs font-medium px-2 py-1 rounded-full ml-auto" 
                                  x-show="!sidebarCollapsed">
                                {{ $pendingOrders }}
                            </span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-box w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Products</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-tags w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Categories</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-users w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Customers</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- System Menu -->
        <div class="mb-6">
            <small class="text-white/50 font-bold uppercase tracking-wider block px-3 mb-2" 
                   x-show="!sidebarCollapsed">System</small>
            <ul class="space-y-1">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.general') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-cog w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Settings</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.profile') }}" 
                       class="flex items-center px-3 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.profile') ? 'bg-white/25' : 'hover:bg-white/10' }}">
                        <i class="fas fa-user w-6"></i>
                        <span class="sidebar-text ml-3" x-show="!sidebarCollapsed">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- User Profile Section (Sticky at bottom) -->
        <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-white/25 bg-inherit">
            <div class="flex items-center">
                @if(auth('admin')->user()->avatar)
                    <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                         class="w-10 h-10 rounded-full object-cover mr-3" 
                         alt="{{ auth('admin')->user()->name }}">
                @else
                    <div class="w-10 h-10 rounded-full bg-white/25 flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                @endif
                <div x-show="!sidebarCollapsed" x-transition>
                    <h6 class="text-sm font-semibold mb-0">{{ auth('admin')->user()->name }}</h6>
                    <small class="text-white/75 text-xs">{{ auth('admin')->user()->role }}</small>
                </div>
            </div>
        </div>
    </div>
</aside>

@push('styles')
<style>
    /* Custom scrollbar for sidebar */
    .sidebar .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }
    
    .sidebar .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }
    
    .sidebar .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
    
    /* Sidebar collapsed state */
    .sidebar.collapsed {
        width: 80px !important;
    }
    
    .sidebar.collapsed .sidebar-text {
        display: none;
    }
    
    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 0.75rem;
    }
    
    .sidebar.collapsed .nav-link i {
        margin: 0;
        width: auto;
    }
    
    .sidebar.collapsed .badge {
        display: none;
    }
    
    .sidebar.collapsed .avatar {
        margin-right: 0;
    }
    
    .sidebar.collapsed .user-info {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebar', () => ({
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
                
                // Dispatch event for main content to adjust
                window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                    detail: { collapsed: this.sidebarCollapsed } 
                }));
            }
        }));
    });
</script>
@endpush