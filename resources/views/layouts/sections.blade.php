

<!-- Product Details Page -->
<div id="productDetailsPage" class="page hidden">
    <div class="max-w-6xl mx-auto">
        <button id="backToProducts" class="mb-6 text-primary hover:underline flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </button>

        <div id="productDetailsContent">
            <!-- Product details will be dynamically loaded here -->
        </div>
    </div>
</div>

<!-- Categories Page -->
<div id="categoriesPage" class="page hidden">
    <h2 class="text-3xl font-bold mb-8">Product Categories</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="category-large bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-8 text-white">
            <div class="flex items-center mb-4">
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-laptop text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold">Electronics</h3>
            </div>
            <p class="mb-6 opacity-90">Latest smartphones, laptops, tablets, smartwatches, and accessories.</p>
            <a href="#"
                class="inline-block bg-white text-blue-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                data-category="electronics">Browse Electronics</a>
        </div>

        <div class="category-large bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center mb-4">
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-tshirt text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold">Fashion</h3>
            </div>
            <p class="mb-6 opacity-90">Clothing, shoes, bags, jewelry, and accessories for all styles.</p>
            <a href="#"
                class="inline-block bg-white text-purple-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                data-category="fashion">Browse Fashion</a>
        </div>

        <div class="category-large bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-8 text-white">
            <div class="flex items-center mb-4">
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-home text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold">Home & Kitchen</h3>
            </div>
            <p class="mb-6 opacity-90">Furniture, kitchen appliances, home decor, bedding, and more.</p>
            <a href="#"
                class="inline-block bg-white text-green-600 font-bold px-5 py-2 rounded-lg hover:bg-gray-100 transition-all-300"
                data-category="home">Browse Home & Kitchen</a>
        </div>
    </div>
</div>

<!-- Orders Page -->
<div id="ordersPage" class="page hidden">
    <h2 class="text-3xl font-bold mb-8">My Orders</h2>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold">Order Status</h3>
            <p class="text-gray-500 dark:text-gray-400">Track your recent orders</p>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <div class="flex items-center mb-2">
                        <span class="font-bold text-lg mr-4">#ORD-78945</span>
                        <span
                            class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-full text-sm font-medium">Delivered</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Placed on Jan 10, 2026 • 3 items • Total:
                        $245.99</p>
                </div>
                <button
                    class="mt-4 md:mt-0 px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all-300">View
                    Details</button>
            </div>

            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <div class="flex items-center mb-2">
                        <span class="font-bold text-lg mr-4">#ORD-78944</span>
                        <span
                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 rounded-full text-sm font-medium">Shipped</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Placed on Jan 8, 2026 • 1 item • Total: $89.99
                    </p>
                </div>
                <button
                    class="mt-4 md:mt-0 px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all-300">Track
                    Order</button>
            </div>

            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <div class="flex items-center mb-2">
                        <span class="font-bold text-lg mr-4">#ORD-78943</span>
                        <span
                            class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 rounded-full text-sm font-medium">Processing</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Placed on Jan 5, 2026 • 5 items • Total: $432.50
                    </p>
                </div>
                <button
                    class="mt-4 md:mt-0 px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all-300">View
                    Details</button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Page (Requires Login) -->
<div id="checkoutPage" class="page hidden">
    <h2 class="text-3xl font-bold mb-8">Checkout</h2>
    <div class="text-center py-12 bg-gray-100 dark:bg-gray-800 rounded-xl">
        <i class="fas fa-lock text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold mb-2">Login Required</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Please login or create an account to proceed to
            checkout.</p>
        <div class="flex justify-center space-x-4">
            <a href="#" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition-all-300"
                data-page="login">Login</a>
            <a href="#"
                class="px-6 py-3 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all-300"
                data-page="signup">Sign Up</a>
        </div>
    </div>
</div>