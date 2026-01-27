<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase - Online Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#8b5cf6',
                        accent: '#10b981',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }

        /* Hide scrollbar but keep functionality */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Product card hover effect */
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Dark mode adjustments */
        .dark .product-card {
            background-color: #1f2937;
        }

        .dark .cart-sidebar {
            background-color: #111827;
        }

        /* Custom animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.3s forwards;
        }

        /* Pulse animation for cart update */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">
    <!-- Theme Toggle -->
    <div class="fixed top-5 right-5 z-50">
        <button id="themeToggle"
            class="p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all-300">
            <i id="sunIcon" class="fas fa-sun text-yellow-500"></i>
            <i id="moonIcon" class="fas fa-moon text-gray-700 dark:text-gray-300 hidden"></i>
        </button>
    </div>

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-bag text-2xl text-primary"></i>
                    <h1 class="text-2xl font-bold">Shop<span class="text-secondary">Ease</span></h1>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-xl mx-8">
                    <div class="relative w-full">
                        <input type="text" id="searchInput" placeholder="Search products..."
                            class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Navigation & Cart -->
                <div class="flex items-center space-x-6">
                    <!-- Navigation Links -->
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route("customer.home.index") }}" class="nav-link active" data-page="home">Home</a>
                        <a href="{{ route("customer.products.index") }}" class="nav-link" data-page="products">Products</a>
                        <a href="#" class="nav-link" data-page="categories">Categories</a>
                        <a href="#" class="nav-link" data-page="orders">My Orders</a>
                    </nav>

                    <!-- Cart Icon -->
                    <div class="relative">
                        <button id="cartToggle"
                            class="relative p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cartCount"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>
                    </div>

                    <!-- User Account -->
                    <div class="relative">
                        <button id="userMenuToggle"
                            class="flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="hidden md:inline">Account</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50">
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                data-page="login">Login</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                data-page="signup">Sign Up</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                data-page="orders">Order Status</a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuToggle" class="md:hidden text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div id="mobileSearch" class="md:hidden mt-4 hidden">
                <div class="relative">
                    <input type="text" placeholder="Search products..."
                        class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="md:hidden mt-4 hidden">
                <div class="flex flex-col space-y-3">
                    <a href="#" class="nav-link-mobile active" data-page="home">Home</a>
                    <a href="#" class="nav-link-mobile" data-page="products">Products</a>
                    <a href="#" class="nav-link-mobile" data-page="categories">Categories</a>
                    <a href="#" class="nav-link-mobile" data-page="orders">My Orders</a>
                    <a href="#" class="nav-link-mobile" data-page="login">Login</a>
                    <a href="#" class="nav-link-mobile" data-page="signup">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>
    <!-- cart slider -->
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
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
                            class="px-4 py-2 rounded-l-lg flex-1 text-gray-800">
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
</body>

</html>