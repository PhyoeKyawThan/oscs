<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShopEase - Online Store</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }
        
        /* Smooth transitions */
        .transition-all-300 { transition: all 0.3s ease; }
        
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Product card hover effect */
        .product-card { transition: transform 0.3s, box-shadow 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        
        /* Dark mode adjustments */
        .dark .product-card { background-color: #1f2937; }
        .dark .cart-sidebar { background-color: #111827; }
        
        /* Custom animations */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .slide-in { animation: slideIn 0.3s forwards; }
        
        /* Pulse animation for cart update */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation { animation: pulse 0.5s ease-in-out; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">

    <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-bag text-2xl text-primary"></i>
                    <h1 class="text-2xl font-bold">Shop<span class="text-secondary">Ease</span></h1>
                </div>

                <div class="hidden md:flex flex-1 max-w-xl mx-8">
                    <div class="relative w-full">
                        <input type="text" id="searchInput" placeholder="Search products..."
                            class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route("customer.home.index") }}" class="nav-link active" data-page="home">Home</a>
                        <a href="{{ route("products.index") }}" class="nav-link" data-page="products">Products</a>
                        @if (auth()->user())
                            <a href="#" class="nav-link" data-page="orders">My Orders</a>
                        @endif
                    </nav>

                    <div class="relative">
                        <button id="cartToggle"
                            class="relative p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cartCount"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>
                    </div>

                    <div class="relative">
                        <button data-dropdown-toggle="userMenu" data-dropdown-target="userMenu"
                            class="flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="hidden md:inline">Account</span>
                        </button>

                        <div id="userMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50">
                            @if (!auth()->user())
                                <a href="{{ route('customer.login.index') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    data-page="login">Login</a>
                                <a href="{{ route('customer.signup.index') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-page="signup">Sign
                                    Up</a>
                            @else
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    data-page="orders">Order Status</a>
                                <hr class="my-2 border-gray-200 dark:border-gray-700">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
                                </form>
                            @endif
                        </div>
                    </div>

                    <button type="button" id="theme-toggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                        aria-label="Toggle dark mode">
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <button id="mobileMenuToggle" class="md:hidden text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <div id="mobileSearch" class="md:hidden mt-4 hidden">
                <div class="relative">
                    <input type="text" placeholder="Search products..."
                        class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div id="mobileMenu" class="md:hidden mt-4 hidden">
                <div class="flex flex-col space-y-3">
                    <a href="#" class="nav-link-mobile active" data-page="home">Home</a>
                    <a href="#" class="nav-link-mobile" data-page="products">Products</a>
                    <a href="#" class="nav-link-mobile" data-page="orders">My Orders</a>
                    <a href="#" class="nav-link-mobile" data-page="login">Login</a>
                    <a href="#" class="nav-link-mobile" data-page="signup">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 dark:bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-shopping-bag text-2xl text-primary"></i>
                        <h2 class="text-2xl font-bold">Shop<span class="text-secondary">Ease</span></h2>
                    </div>
                    <p class="text-gray-400">Your one-stop shop for all your needs. Quality products at great prices.
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300"
                                data-page="home">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300"
                                data-page="products">Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300"
                                data-page="categories">Categories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300"
                                data-page="orders">Order Status</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300">Shipping Policy</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all-300">Returns & Refunds</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Stay Connected</h3>
                    <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest updates.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email"
                            class="px-4 py-2 rounded-l-lg flex-1 text-gray-800 dark:text-gray-200 dark:bg-gray-700">
                        <button
                            class="bg-primary px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-all-300">Subscribe</button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 ShopEase. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @vite(['resources/js/app.js'])
    @include('layouts.cart')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addToCart = function (productId, quantity = 1) {
                if (window.cartManager) {
                    return window.cartManager.addToCart(productId, quantity);
                }
                return false;
            };
        });

        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            function updateThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');

                if (isDark) {
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                } else {
                    themeToggleLightIcon.classList.add('hidden');
                    themeToggleDarkIcon.classList.remove('hidden');
                }
            }

            updateThemeIcons();

            themeToggle.addEventListener('click', function () {
                const isDark = document.documentElement.classList.contains('dark');

                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }

                updateThemeIcons();
            });

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                    updateThemeIcons();
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>