<nav class="bg-white shadow-sm sticky top-0 z-[999]">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    x-data="{ mobileMenuOpen: false }">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Right Side Actions -->
            <div class="flex items-center ml-auto space-x-3">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 transform translate-x-1/2 -translate-y-1/2 bg-red-600 text-white text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ \App\Models\Orders::where('status', 'Pending')->count() }}
                        </span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h6 class="font-semibold text-gray-900">Notifications</h6>
                        </div>
                        
                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            @php
                                $pendingOrders = \App\Models\Orders::where('status', 'Pending')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($pendingOrders as $order)
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="flex items-start px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-0"
                                   @click="open = false">
                                    <div class="flex-shrink-0">
                                        <div class="bg-blue-50 p-2 rounded-lg">
                                            <i class="fas fa-shopping-bag text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 ml-3">
                                        <p class="text-sm font-medium text-gray-900 mb-1">New Order #{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-8 px-4">
                                    <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500 text-sm">No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Footer -->
                        <div class="p-3 border-t border-gray-200">
                            <a href="{{ route('admin.orders.index') }}" 
                               class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                View All Orders
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none">
                        @if(auth('admin')->user()->avatar)
                            <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" 
                                 class="w-8 h-8 rounded-full object-cover" 
                                 alt="{{ auth('admin')->user()->name }}">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        @endif
                        <span class="hidden md:inline text-sm font-medium">{{ auth('admin')->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <ul x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        
                        <li>
                            <a href="{{ route('admin.profile') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                               @click="open = false">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                               @click="open = false">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="border-t border-gray-200 my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                                @csrf
                                <a href="#" 
                                   class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                                   @click.prevent="document.getElementById('logoutForm').submit()">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar (Offcanvas) -->
<div x-show="mobileMenuOpen" 
     x-data="{ mobileMenuOpen: false }"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform -translate-x-full"
     x-transition:enter-end="transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform translate-x-0"
     x-transition:leave-end="transform -translate-x-full"
     class="fixed inset-0 z-[1001] lg:hidden"
     @keydown.escape.window="mobileMenuOpen = false">
    
    <!-- Backdrop -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
         @click="mobileMenuOpen = false"></div>
    
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900">{{ config('app.name') }}</h5>
            <button @click="mobileMenuOpen = false" 
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="overflow-y-auto h-[calc(100vh-64px)]">
            @include('admin.layouts.sidebar')
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Close mobile menu on window resize if open
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) { // lg breakpoint
            const mobileMenuOpen = document.querySelector('[x-data="{ mobileMenuOpen: false }"]')?.__x?.$data.mobileMenuOpen;
            if (mobileMenuOpen) {
                mobileMenuOpen = false;
            }
        }
    });
</script>
@endpush