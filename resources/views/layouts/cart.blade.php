<!-- Cart Sidebar -->
    <div id="cartSidebar"
        class="fixed top-0 right-0 h-full w-full md:w-96 bg-white dark:bg-gray-800 shadow-2xl z-50 transform translate-x-full transition-transform duration-300">
        <div class="h-full flex flex-col">
            <!-- Cart Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold">Shopping Cart</h3>
                    <button id="closeCart" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p id="cartItemCount" class="text-gray-500 dark:text-gray-400 mt-2">0 items</p>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-6" id="cartItemsContainer">
                <!-- Cart items will be dynamically loaded here -->
                <div class="text-center py-12 text-gray-500 dark:text-gray-400" id="emptyCartMessage">
                    <i class="fas fa-shopping-cart text-5xl mb-4"></i>
                    <p class="text-lg">Your cart is empty</p>
                    <p class="text-sm mt-1">Add some products to get started</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between mb-4">
                    <span class="font-bold">Subtotal</span>
                    <span id="cartSubtotal" class="font-bold text-lg">$0.00</span>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Shipping and taxes calculated at checkout</p>
                </div>

                <div class="space-y-3">
                    <button id="viewCart"
                        class="w-full py-3 border border-primary text-primary rounded-lg font-bold hover:bg-primary hover:text-white transition-all-300">View
                        Cart Details</button>
                    <button id="checkoutBtn"
                        class="w-full py-3 bg-primary text-white rounded-lg font-bold hover:bg-blue-700 transition-all-300">Proceed
                        to Checkout</button>
                </div>

                <div class="mt-6 text-center">
                    <a href="#" class="text-primary font-medium hover:underline" data-page="products">Continue
                        Shopping</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
