<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <a href="{{ route("products.index") }}" class="nav-link" data-page="products">Products</a>
                        <!-- <a href="#" class="nav-link" data-page="categories">Categories</a> -->
                        @if (auth()->user())
                            <a href="#" class="nav-link" data-page="orders">My Orders</a>
                        @endif
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
                        <button data-dropdown-toggle="userMenu" data-dropdown-target="userMenu"
                            class="flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <div class="h-8 w-8 bg-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <span class="hidden md:inline">Account</span>
                        </button>

                        <!-- Dropdown Menu -->
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
                    <!-- <a href="#" class="nav-link-mobile" data-page="categories">Categories</a> -->
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
    @include('layouts.cart')
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>
    <script>
        class CartManager {
            constructor() {
                this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                this.init();
            }

            init() {
                this.bindEvents();
                this.loadCartCount();
            }

            bindEvents() {
                // Cart toggle button
                const cartToggle = document.getElementById('cartToggle');
                if (cartToggle) {
                    cartToggle.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.showCart();
                    });
                }

                // Add this to bindEvents() method
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.add-to-cart')) {
                        e.preventDefault();
                        const button = e.target.closest('.add-to-cart');
                        const productId = button.dataset.productId;
                        if (productId && !button.disabled) {
                            this.addToCart(productId);
                        }
                    }
                });

                // Close cart sidebar
                document.getElementById('closeCart')?.addEventListener('click', () => this.hideCart());
                document.getElementById('cartOverlay')?.addEventListener('click', () => this.hideCart());

                // View cart details button
                document.getElementById('viewCart')?.addEventListener('click', () => {
                    window.location.href = '/cart';
                });

                // Checkout button
                document.getElementById('checkoutBtn')?.addEventListener('click', () => {
                    this.checkout();
                });

                // Continue shopping
                document.querySelector('.continue-shopping')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.hideCart();
                });
            }

            async loadCartCount() {
                try {
                    const response = await fetch('/cart/data', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.updateCartCountUI(data.cart_count);
                        return data.cart_count;
                    }
                } catch (error) {
                    console.error('Error loading cart count:', error);
                }
                return 0;
            }

            updateCartCountUI(count) {
                const cartCountElement = document.getElementById('cartCount');
                if (cartCountElement) {
                    cartCountElement.textContent = count;
                    // Show/hide based on count
                    if (count > 0) {
                        cartCountElement.classList.remove('hidden');
                    } else {
                        cartCountElement.classList.add('hidden');
                    }
                }
            }

            showCart() {
                const cartSidebar = document.getElementById('cartSidebar');
                const cartOverlay = document.getElementById('cartOverlay');

                if (cartSidebar && cartOverlay) {
                    cartSidebar.classList.remove('translate-x-full');
                    cartOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    this.loadCartItems();
                }
            }

            hideCart() {
                const cartSidebar = document.getElementById('cartSidebar');
                const cartOverlay = document.getElementById('cartOverlay');

                if (cartSidebar && cartOverlay) {
                    cartSidebar.classList.add('translate-x-full');
                    cartOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            }

            async loadCartItems() {
                try {
                    const response = await fetch('/cart/data', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.updateCartUI(data);
                    }
                } catch (error) {
                    console.error('Error loading cart items:', error);
                }
            }

            updateCartUI(data) {
                const cartItemsContainer = document.getElementById('cartItemsContainer');
                const emptyCartMessage = document.getElementById('emptyCartMessage');
                const cartItemCount = document.getElementById('cartItemCount');
                const cartSubtotal = document.getElementById('cartSubtotal');
                const cartTotal = document.getElementById('cartTotal');

                if (!cartItemsContainer || !cartItemCount || !cartSubtotal || !cartTotal) return;

                // Update cart header info
                cartItemCount.textContent = `${data.cart_count} ${data.cart_count === 1 ? 'item' : 'items'}`;
                // Calculate totals
                const subtotal = parseFloat(data.cart_total);
                window.data = data;
                console.log(typeof (subtotal))
                console.log(subtotal)
                const shipping = subtotal > 50 ? 0 : 5.99; // Free shipping over $50
                const total = parseFloat(subtotal + shipping);
                cartSubtotal.textContent = `${subtotal.toFixed(2)} MMKS`;
                document.getElementById('cartShipping').textContent = shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`;
                cartTotal.textContent = `${total.toFixed(2)} MMKS`;

                // Update cart items
                if (data.cart_count > 0 && data.cart_items) {
                    // Hide empty message
                    if (emptyCartMessage) {
                        emptyCartMessage.classList.add('hidden');
                    }

                    let html = '';
                    Object.values(data.cart_items).forEach(item => {
                        const itemTotal = (item.price * item.quantity).toFixed(2);
                        html += `
                <div class="cart-item flex items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700" 
                     data-product-id="${item.id}">
                    <!-- Product Image -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="${item.image}" 
                             alt="${item.name}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Product Info -->
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-sm mb-1">${item.name}</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-2">${item.category}</p>
                        <p class="text-primary font-bold">${parseFloat(item.price).toFixed(2)} MMKS each</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total: ${itemTotal} MMKS</p>
                    </div>
                    
                    <!-- Quantity Controls -->
                    <div class="ml-4 flex flex-col items-end">
                        <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg mb-2">
                            <button class="px-3 py-1 text-gray-500 hover:text-primary decrease-qty" 
                                    data-product-id="${item.id}">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="number" 
                                   value="${item.quantity}" 
                                   min="1" max="${item.stock}"
                                   class="w-12 text-center border-0 focus:ring-0 bg-transparent quantity-input"
                                   data-product-id="${item.id}"
                                   readonly>
                            <button class="px-3 py-1 text-gray-500 hover:text-primary increase-qty"
                                    data-product-id="${item.id}">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                        
                        <!-- Remove Button -->
                        <button class="text-red-500 hover:text-red-700 text-sm remove-from-cart"
                                data-product-id="${item.id}">
                            <i class="fas fa-trash-alt mr-1"></i> Remove
                        </button>
                    </div>
                </div>`;
                    });

                    cartItemsContainer.innerHTML = html;

                    // Bind events to dynamic elements
                    this.bindCartItemEvents();
                } else {
                    // Show empty message
                    if (emptyCartMessage) {
                        emptyCartMessage.classList.remove('hidden');
                    }
                    cartItemsContainer.innerHTML = '';
                    cartItemsContainer.appendChild(emptyCartMessage);
                }
            }

            bindCartItemEvents() {
                // Quantity decrease
                cartItemsContainer.querySelectorAll('.decrease-qty').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const productId = e.currentTarget.dataset.productId;
                        const input = cartItemsContainer.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                        let newQty = parseInt(input.value) - 1;

                        if (newQty >= 1) {
                            this.updateCartItem(productId, newQty);
                        }
                    });
                });

                // Quantity increase
                cartItemsContainer.querySelectorAll('.increase-qty').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const productId = e.currentTarget.dataset.productId;
                        const input = cartItemsContainer.querySelector(`.quantity-input[data-product-id="${productId}"]`);
                        let newQty = parseInt(input.value) + 1;

                        this.updateCartItem(productId, newQty);
                    });
                });

                // Quantity input change
                cartItemsContainer.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', (e) => {
                        const productId = e.target.dataset.productId;
                        let newQty = parseInt(e.target.value);

                        if (newQty >= 1) {
                            this.updateCartItem(productId, newQty);
                        }
                    });
                });

                // Remove item
                cartItemsContainer.querySelectorAll('.remove-from-cart').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const productId = e.currentTarget.dataset.productId;
                        if (confirm('Are you sure you want to remove this item from your cart?')) {
                            this.removeFromCart(productId);
                        }
                    });
                });
            }

            async addToCart(productId, quantity = 1) {
                try {
                    const response = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showNotification(data.message, 'success');
                        this.updateCartCountUI(data.cart_count);

                        // Update cart sidebar if open
                        const cartSidebar = document.getElementById('cartSidebar');
                        if (cartSidebar && !cartSidebar.classList.contains('translate-x-full')) {
                            this.loadCartItems();
                        }

                        return true;
                    } else {
                        this.showNotification(data.message, 'error');
                        return false;
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    this.showNotification('An error occurred. Please try again.', 'error');
                    return false;
                }
            }

            async updateCartItem(productId, quantity) {
                try {
                    const response = await fetch('/cart/update', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.updateCartCountUI(data.cart_count);
                        this.updateCartUI(data);
                        this.showNotification('Quantity updated', 'success');
                    } else {
                        this.showNotification(data.message, 'error');
                        // Reload cart to get correct values
                        this.loadCartItems();
                    }
                } catch (error) {
                    console.error('Error updating cart:', error);
                    this.showNotification('An error occurred. Please try again.', 'error');
                }
            }

            async removeFromCart(productId) {
                try {
                    const response = await fetch('/cart/remove', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.updateCartCountUI(data.cart_count);
                        this.updateCartUI(data);
                        this.showNotification('Item removed from cart', 'success');
                    } else {
                        this.showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error removing from cart:', error);
                    this.showNotification('An error occurred. Please try again.', 'error');
                }
            }

            checkout() {
                // Check if cart has items
                const cartCount = parseInt(document.getElementById('cartCount').textContent) || 0;
                if (cartCount === 0) {
                    this.showNotification('Your cart is empty', 'error');
                    return;
                }

                window.location.href = '/checkout';
            }

            showNotification(message, type = 'success') {
                // Remove existing notifications
                document.querySelectorAll('.cart-notification').forEach(el => el.remove());

                // Create notification element
                const notification = document.createElement('div');
                notification.className = `cart-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    } z-50 transform transition-transform duration-300`;
                notification.textContent = message;
                notification.style.transform = 'translateX(100%)';

                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 10);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }
        }

        // Initialize cart manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            window.cartManager = new CartManager();

            // Make addToCart function globally available for inline onclick
            window.addToCart = function (productId, quantity = 1) {
                if (window.cartManager) {
                    return window.cartManager.addToCart(productId, quantity);
                }
                return false;
            };
        });
    </script>
    @stack('scripts')
</body>

</html>