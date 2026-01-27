@extends('layouts.template')
@section('content')
    <!-- Cart Page -->
    <div id="cartPage" class="page">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold">Shopping Cart</h2>
                <div class="flex items-center text-gray-500 dark:text-gray-400 mt-2">
                    <a href="{{ route('products.index') }}" class="text-primary hover:underline">
                        Continue Shopping
                    </a>
                    <span class="mx-2">•</span>
                    <span id="cartItemCountText">{{ count($cartItems) }} items in your cart</span>
                </div>
            </div>

            @if(count($cartItems) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <!-- Cart Items Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                            <!-- Desktop Table -->
                            <div class="hidden md:block">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700">
                                            <th class="py-4 px-6 text-left font-medium text-gray-700 dark:text-gray-300">Product
                                            </th>
                                            <th class="py-4 px-6 text-left font-medium text-gray-700 dark:text-gray-300">Price
                                            </th>
                                            <th class="py-4 px-6 text-left font-medium text-gray-700 dark:text-gray-300">
                                                Quantity</th>
                                            <th class="py-4 px-6 text-left font-medium text-gray-700 dark:text-gray-300">Total
                                            </th>
                                            <th class="py-4 px-6 text-left font-medium text-gray-700 dark:text-gray-300"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartTableBody">
                                        @foreach($cartItems as $item)
                                            <tr class="border-t border-gray-200 dark:border-gray-700 cart-item"
                                                data-product-id="{{ $item['id'] }}">
                                                <!-- Product Info -->
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center">
                                                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                                                class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="ml-4">
                                                            <h4 class="font-bold text-sm">{{ $item['name'] }}</h4>
                                                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                                                {{ $item['category'] }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                                Stock:
                                                                @if($item['stock'] > 10)
                                                                    <span class="text-green-500">In stock</span>
                                                                @elseif($item['stock'] > 0)
                                                                    <span class="text-yellow-500">Only {{ $item['stock'] }} left</span>
                                                                @else
                                                                    <span class="text-red-500">Out of stock</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Price -->
                                                <td class="py-4 px-6">
                                                    <div class="font-bold text-primary">{{ number_format($item['price'], 2) }} MMKS
                                                    </div>
                                                    @if($item['stock'] === 0)
                                                        <span class="text-xs text-red-500">Out of stock</span>
                                                    @endif
                                                </td>

                                                <!-- Quantity -->
                                                <td class="py-4 px-6">
                                                    <div class="ml-4 flex flex-col items-end">
                                                        <div
                                                            class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg mb-2">
                                                            <button class="px-3 py-1 text-gray-500 hover:text-primary decrease-qty"
                                                                data-product-id="{{ $item['id'] }}">
                                                                <i class="fas fa-minus text-xs"></i>
                                                            </button>
                                                            <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                                max="{{ $item['stock'] }}"
                                                                class="w-12 text-center border-0 focus:ring-0 bg-transparent quantity-input"
                                                                data-product-id="{{ $item['quantity'] }}" readonly>
                                                            <button class="px-3 py-1 text-gray-500 hover:text-primary increase-qty"
                                                                data-product-id="{{ $item['id'] }}">
                                                                <i class="fas fa-plus text-xs"></i>
                                                            </button>
                                                        </div>
                                                        @if($item['quantity'] > $item['stock'])
                                                            <p class="text-xs text-red-500 mt-1">Only {{ $item['stock'] }} available</p>
                                                        @endif
                                                </td>

                                                <!-- Total -->
                                                <td class="py-4 px-6">
                                                    <div class="font-bold text-lg item-total" data-price="{{ $item['price'] }}"
                                                        data-quantity="{{ $item['quantity'] }}">
                                                        {{ number_format($item['price'] * $item['quantity'], 2) }} MMKS
                                                    </div>
                                                </td>

                                                <!-- Actions -->
                                                <td class="py-4 px-6">
                                                    <button class="remove-item text-red-500 hover:text-red-700"
                                                        data-product-id="{{ $item['id'] }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cart Actions -->
                            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
                                <!-- Continue Shopping -->
                                <a href="{{ route('products.index') }}"
                                    class="mb-4 sm:mb-0 px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Continue Shopping
                                </a>

                                <!-- Clear Cart -->
                                <div class="flex space-x-4">
                                    <button id="clearCartBtn"
                                        class="px-6 py-3 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-24">
                                <h3 class="text-xl font-bold mb-4">Order Summary</h3>

                                <!-- Summary Items -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span id="summarySubtotal" class="font-medium">{{ number_format($total, 2) }}
                                            MMKS</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                        <span id="summaryShipping" class="font-medium">
                                            @if($total > 50)
                                                FREE
                                            @else
                                                5.99 MMKS
                                            @endif
                                        </span>
                                    </div>

                                    @if($total > 50)
                                        <div class="flex justify-between text-green-600">
                                            <span>Free Shipping</span>
                                            <span>-5.99 MMKS</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Total -->
                                <div
                                    class="flex justify-between text-xl font-bold border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                                    <span>Total</span>
                                    <span id="summaryTotal"
                                        class="text-primary">{{ number_format($total + ($total > 50 ? 0 : 5.99), 2) }}
                                        MMKS</span>
                                </div>

                                <!-- Proceed to Checkout -->
                                <a href="{{ route('checkout.index') }}"
                                    class="block w-full py-3 bg-primary text-white rounded-lg font-bold hover:bg-blue-700 transition-all-300 text-center mb-4">
                                    Proceed to Checkout
                                </a>

                                <!-- Payment Methods -->
                                <div class="mb-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">We accept:</p>
                                    <div class="flex space-x-2">
                                        <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                        <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                        <i class="fab fa-cc-amex text-2xl text-blue-400"></i>
                                        <i class="fab fa-cc-paypal text-2xl text-blue-500"></i>
                                        <i class="fab fa-cc-discover text-2xl text-orange-600"></i>
                                    </div>
                                </div>

                                <!-- Promo Code -->
                                <div class="mb-6">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Promo Code
                                    </label>
                                    <div class="flex">
                                        <input type="text" id="promoCode"
                                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                            placeholder="Enter code">
                                        <button type="button" id="applyPromoBtn"
                                            class="px-4 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-r-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                            Apply
                                        </button>
                                    </div>
                                    <div id="promoMessage" class="mt-2 text-sm hidden"></div>
                                </div>

                                <!-- Security Note -->
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        Secure checkout
                                    </p>
                                </div>
                            </div>

                            <!-- Customer Support -->
                            <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                                <h4 class="font-bold mb-2 flex items-center">
                                    <i class="fas fa-headset mr-2 text-primary"></i>
                                    Need Help?
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    Our customer support team is here to help.
                                </p>
                                <div class="space-y-2">
                                    <a href="tel:+1234567890"
                                        class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
                                        <i class="fas fa-phone mr-2"></i> (123) 456-7890
                                    </a>
                                    <a href="mailto:support@example.com"
                                        class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
                                        <i class="fas fa-envelope mr-2"></i> support@example.com
                                    </a>
                                    <a href="#"
                                        class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
                                        <i class="fas fa-comments mr-2"></i> Live Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            @else
                    <!-- Empty Cart -->
                    <div class="text-center py-12">
                        <div
                            class="w-32 h-32 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shopping-cart text-5xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Add some products to get started</p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('products.index') }}"
                                class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition-all-300">
                                Browse Products
                            </a>
                            <a href="{{ route('categories.index') }}"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                View Categories
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
@endsection

    @push('scripts')
        <script>
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
    @endpush