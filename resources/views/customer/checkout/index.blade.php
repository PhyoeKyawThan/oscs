@extends('layouts.template')
@section('content')
    <!-- Checkout Page -->
    <div id="checkoutPage" class="page">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold">Checkout</h2>
                <p class="text-gray-500 dark:text-gray-400">Complete your purchase</p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                            1
                        </div>
                        <span class="font-medium">Cart</span>
                    </div>
                    <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 mx-4"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                            2
                        </div>
                        <span class="font-medium">Delivery</span>
                    </div>
                    <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 mx-4"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            3
                        </div>
                        <span class="text-gray-500 dark:text-gray-400">Payment</span>
                    </div>
                </div>
            </div>

            @if($cartItems && count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Summary -->
                <div class="lg:col-span-2">
                    <!-- Cart Items Review -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                        
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                            @foreach($cartItems as $item)
                            <div class="flex items-center pb-4 border-b border-gray-100 dark:border-gray-700">
                                <!-- Product Image -->
                                <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ $item['image'] }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Product Info -->
                                <div class="ml-4 flex-1">
                                    <h4 class="font-bold text-sm">{{ $item['name'] }}</h4>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">{{ $item['category'] }}</p>
                                    <p class="text-primary font-bold">${{ number_format($item['price'], 2) }} each</p>
                                </div>
                                
                                <!-- Quantity and Total -->
                                <div class="text-right">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                        Qty: {{ $item['quantity'] }}
                                    </p>
                                    <p class="font-bold text-lg">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Edit Cart Button -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('cart.index') }}" 
                               class="text-primary hover:underline font-medium">
                                <i class="fas fa-edit mr-2"></i> Edit Cart
                            </a>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                        <h3 class="text-xl font-bold mb-4">Delivery Information</h3>
                        
                        <form id="deliveryForm">
                            @csrf
                            
                            <!-- Name -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        First Name *
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Last Name *
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Email Address *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Phone Number *
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                    Street Address *
                                </label>
                                <input type="text" 
                                       name="address" 
                                       value="{{ old('address', auth()->user()->address ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                       required>
                            </div>
                            
                            <!-- City, State, Zip -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        City *
                                    </label>
                                    <input type="text" 
                                           name="city" 
                                           value="{{ old('city', auth()->user()->city ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        State *
                                    </label>
                                    <input type="text" 
                                           name="state" 
                                           value="{{ old('state', auth()->user()->state ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        ZIP Code *
                                    </label>
                                    <input type="text" 
                                           name="zip_code" 
                                           value="{{ old('zip_code', auth()->user()->zip_code ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Country -->
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                    Country *
                                </label>
                                <select name="country" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                        required>
                                    <option value="">Select Country</option>
                                    <option value="US" {{ (old('country', auth()->user()->country ?? '') == 'US') ? 'selected' : '' }}>United States</option>
                                    <option value="CA" {{ (old('country', auth()->user()->country ?? '') == 'CA') ? 'selected' : '' }}>Canada</option>
                                    <option value="UK" {{ (old('country', auth()->user()->country ?? '') == 'UK') ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="AU" {{ (old('country', auth()->user()->country ?? '') == 'AU') ? 'selected' : '' }}>Australia</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                            
                            <!-- Delivery Method -->
                            <div class="mb-6">
                                <label class="block text-gray-700 dark:text-gray-300 mb-4 text-sm font-medium">
                                    Delivery Method *
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary cursor-pointer">
                                        <input type="radio" name="delivery_method" value="standard" class="mr-3" checked>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <span class="font-medium">Standard Delivery</span>
                                                <span class="font-bold">$5.99</span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">3-5 business days</p>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary cursor-pointer">
                                        <input type="radio" name="delivery_method" value="express" class="mr-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <span class="font-medium">Express Delivery</span>
                                                <span class="font-bold">$12.99</span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">1-2 business days</p>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary cursor-pointer">
                                        <input type="radio" name="delivery_method" value="pickup" class="mr-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <span class="font-medium">Store Pickup</span>
                                                <span class="font-bold">FREE</span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pick up from our store</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Delivery Instructions -->
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                    Delivery Instructions (Optional)
                                </label>
                                <textarea name="delivery_instructions" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                          placeholder="Add delivery instructions, gate codes, etc."></textarea>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Payment Method</h3>
                        
                        <!-- Payment Tabs -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <nav class="flex space-x-8">
                                    <button type="button" 
                                            class="payment-tab active py-2 font-medium text-primary border-b-2 border-primary"
                                            data-tab="credit-card">
                                        Credit/Debit Card
                                    </button>
                                    <button type="button" 
                                            class="payment-tab py-2 font-medium text-gray-500 hover:text-primary border-b-2 border-transparent"
                                            data-tab="paypal">
                                        PayPal
                                    </button>
                                    <button type="button" 
                                            class="payment-tab py-2 font-medium text-gray-500 hover:text-primary border-b-2 border-transparent"
                                            data-tab="cod">
                                        Cash on Delivery
                                    </button>
                                </nav>
                            </div>
                        </div>
                        
                        <!-- Credit Card Form -->
                        <div id="credit-card-form" class="payment-form">
                            <form id="paymentForm">
                                <div class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Card Number *
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="card_number"
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary pl-12"
                                               placeholder="1234 5678 9012 3456"
                                               maxlength="19"
                                               required>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="far fa-credit-card text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <div class="flex space-x-1">
                                                <i class="fab fa-cc-visa text-blue-600"></i>
                                                <i class="fab fa-cc-mastercard text-red-600"></i>
                                                <i class="fab fa-cc-amex text-blue-400"></i>
                                                <i class="fab fa-cc-discover text-orange-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Expiry Date *
                                        </label>
                                        <input type="text" 
                                               name="expiry_date"
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                               placeholder="MM/YY"
                                               maxlength="5"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            CVV *
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   name="cvv"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                                   placeholder="123"
                                                   maxlength="4"
                                                   required>
                                            <button type="button" 
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                    onclick="toggleCVVHelp()">
                                                <i class="far fa-question-circle text-gray-400"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Cardholder Name *
                                    </label>
                                    <input type="text" 
                                           name="cardholder_name"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                                           placeholder="Name on card"
                                           required>
                                </div>
                                
                                <!-- Save Card (Optional) -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="save_card" class="mr-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Save card for future purchases</span>
                                    </label>
                                </div>
                            </form>
                        </div>
                        
                        <!-- PayPal Form -->
                        <div id="paypal-form" class="payment-form hidden">
                            <div class="text-center py-8">
                                <i class="fab fa-paypal text-5xl text-blue-600 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    You will be redirected to PayPal to complete your payment securely.
                                </p>
                                <button type="button" 
                                        class="px-8 py-3 bg-yellow-400 text-gray-800 rounded-lg font-bold hover:bg-yellow-500 transition-colors">
                                    Pay with PayPal
                                </button>
                            </div>
                        </div>
                        
                        <!-- Cash on Delivery Form -->
                        <div id="cod-form" class="payment-form hidden">
                            <div class="text-center py-8">
                                <i class="fas fa-money-bill-wave text-5xl text-green-600 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    Pay with cash when your order arrives.
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    An extra $2.00 fee applies for cash on delivery.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       name="terms" 
                                       class="mt-1 mr-2"
                                       required>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    I agree to the <a href="#" class="text-primary hover:underline">Terms and Conditions</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                                </span>
                            </label>
                        </div>
                        
                        <!-- Place Order Button -->
                        <button type="button" 
                                id="placeOrderBtn"
                                class="w-full py-4 bg-primary text-white rounded-lg font-bold hover:bg-blue-700 transition-all-300 flex items-center justify-center">
                            <i class="fas fa-lock mr-2"></i>
                            <span>Place Order</span>
                            <span id="orderSpinner" class="ml-2 hidden">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                        
                        <!-- Security Note -->
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Your payment information is secured with 256-bit SSL encryption
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-24">
                        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                        
                        <!-- Order Items -->
                        <div class="space-y-3 mb-6">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Totals -->
                        <div class="space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <span id="shippingCost" class="font-medium">$5.99</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                <span id="taxAmount" class="font-medium">${{ number_format($tax, 2) }}</span>
                            </div>
                            
                            @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Total -->
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-4 mb-6">
                            <span>Total</span>
                            <span id="orderTotal">${{ number_format($total, 2) }}</span>
                        </div>
                        
                        <!-- Order Summary Details -->
                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-2">
                            <p><i class="fas fa-box mr-2"></i> {{ count($cartItems) }} items in order</p>
                            <p><i class="fas fa-shipping-fast mr-2"></i> Standard delivery: 3-5 days</p>
                            <p><i class="fas fa-undo mr-2"></i> 30-day return policy</p>
                        </div>
                    </div>
                    
                    <!-- Help Section -->
                    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                        <h4 class="font-bold mb-2 flex items-center">
                            <i class="fas fa-question-circle text-blue-500 mr-2"></i>
                            Need Help?
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Contact our customer support for assistance with your order.
                        </p>
                        <div class="space-y-2">
                            <a href="tel:+1234567890" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
                                <i class="fas fa-phone mr-2"></i> +1 (234) 567-890
                            </a>
                            <a href="mailto:support@example.com" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
                                <i class="fas fa-envelope mr-2"></i> support@example.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty Cart Message -->
            <div class="text-center py-12 bg-gray-100 dark:bg-gray-800 rounded-xl">
                <i class="fas fa-shopping-cart text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Add some products to your cart to checkout.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition-all-300">
                    Continue Shopping
                </a>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize checkout page
    initCheckout();
});

function initCheckout() {
    // Payment method tabs
    const paymentTabs = document.querySelectorAll('.payment-tab');
    const paymentForms = document.querySelectorAll('.payment-form');
    
    paymentTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            // Update active tab
            paymentTabs.forEach(t => {
                t.classList.remove('active', 'text-primary', 'border-primary');
                t.classList.add('text-gray-500', 'border-transparent');
            });
            this.classList.add('active', 'text-primary', 'border-primary');
            this.classList.remove('text-gray-500', 'border-transparent');
            
            // Show corresponding form
            paymentForms.forEach(form => {
                form.classList.add('hidden');
            });
            document.getElementById(`${tabName}-form`).classList.remove('hidden');
        });
    });
    
    // Calculate shipping based on delivery method
    const deliveryMethods = document.querySelectorAll('input[name="delivery_method"]');
    deliveryMethods.forEach(method => {
        method.addEventListener('change', updateShippingCost);
    });
    
    // Apply promo code
    document.getElementById('applyPromoBtn').addEventListener('click', applyPromoCode);
    document.getElementById('promoCode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyPromoCode();
        }
    });
    
    // Card number formatting
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = formatted.substring(0, 19);
        });
    }
    
    // Expiry date formatting
    const expiryInput = document.querySelector('input[name="expiry_date"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5);
        });
    }
    
    // CVV formatting
    const cvvInput = document.querySelector('input[name="cvv"]');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
        });
    }
    
    // Place order button
    document.getElementById('placeOrderBtn').addEventListener('click', placeOrder);
}

function updateShippingCost() {
    const selectedMethod = document.querySelector('input[name="delivery_method"]:checked');
    let shippingCost = 0;
    
    if (selectedMethod) {
        switch(selectedMethod.value) {
            case 'standard':
                shippingCost = 5.99;
                break;
            case 'express':
                shippingCost = 12.99;
                break;
            case 'pickup':
                shippingCost = 0;
                break;
        }
    }
    
    // Update UI
    document.getElementById('shippingCost').textContent = 
        shippingCost === 0 ? 'FREE' : `$${shippingCost.toFixed(2)}`;
    
    // Recalculate total
    calculateTotal();
}

function applyPromoCode() {
    const promoCode = document.getElementById('promoCode').value.trim();
    const promoMessage = document.getElementById('promoMessage');
    
    if (!promoCode) {
        promoMessage.textContent = 'Please enter a promo code';
        promoMessage.className = 'mt-2 text-sm text-red-600';
        promoMessage.classList.remove('hidden');
        return;
    }
    
    // Show loading
    promoMessage.textContent = 'Applying promo code...';
    promoMessage.className = 'mt-2 text-sm text-blue-600';
    promoMessage.classList.remove('hidden');
    
    // Simulate API call
    setTimeout(() => {
        // Example promo codes
        const validPromoCodes = {
            'SAVE10': 0.10, // 10% discount
            'SAVE20': 0.20, // 20% discount
            'FREESHIP': 'free_shipping', // Free shipping
            'WELCOME15': 0.15, // 15% welcome discount
        };
        
        if (validPromoCodes[promoCode.toUpperCase()]) {
            const discount = validPromoCodes[promoCode.toUpperCase()];
            
            // In a real app, you would save this to session/cart
            sessionStorage.setItem('appliedPromoCode', promoCode);
            sessionStorage.setItem('promoDiscount', JSON.stringify(discount));
            
            promoMessage.textContent = 'Promo code applied successfully!';
            promoMessage.className = 'mt-2 text-sm text-green-600';
            
            // Recalculate total with discount
            calculateTotal();
        } else {
            promoMessage.textContent = 'Invalid promo code';
            promoMessage.className = 'mt-2 text-sm text-red-600';
        }
    }, 500);
}

function calculateTotal() {
    // Get values from PHP (you would need to pass these via data attributes)
    const subtotal = parseFloat({{ $subtotal }}) || 0;
    
    // Get shipping cost
    const shippingText = document.getElementById('shippingCost').textContent;
    const shippingCost = shippingText === 'FREE' ? 0 : 
                        parseFloat(shippingText.replace('$', '')) || 0;
    
    // Get tax (from PHP)
    const tax = parseFloat({{ $tax }}) || 0;
    
    // Get discount from session storage
    let discount = 0;
    const storedDiscount = sessionStorage.getItem('promoDiscount');
    if (storedDiscount) {
        const discountValue = JSON.parse(storedDiscount);
        if (typeof discountValue === 'number') {
            discount = subtotal * discountValue;
        } else if (discountValue === 'free_shipping') {
            // Free shipping is handled separately
        }
    }
    
    // Calculate total
    let total = subtotal + shippingCost + tax - discount;
    
    // Update total display
    document.getElementById('orderTotal').textContent = `$${total.toFixed(2)}`;
}

function toggleCVVHelp() {
    alert('CVV is the 3-digit (or 4-digit for American Express) number on the back of your card.');
}

async function placeOrder() {
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const orderSpinner = document.getElementById('orderSpinner');
    const termsCheckbox = document.querySelector('input[name="terms"]');
    
    // Validate terms and conditions
    if (!termsCheckbox || !termsCheckbox.checked) {
        alert('Please agree to the Terms and Conditions to proceed.');
        return;
    }
    
    // Validate delivery form
    const deliveryForm = document.getElementById('deliveryForm');
    if (!deliveryForm.checkValidity()) {
        deliveryForm.reportValidity();
        return;
    }
    
    // Show loading
    placeOrderBtn.disabled = true;
    orderSpinner.classList.remove('hidden');
    
    try {
        // Gather order data
        const orderData = {
            delivery: getFormData(deliveryForm),
            payment: getPaymentData(),
            cart: @json($cartItems),
            totals: {
                subtotal: {{ $subtotal }},
                shipping: getShippingCost(),
                tax: {{ $tax }},
                discount: getDiscountAmount(),
                total: getTotalAmount()
            }
        };
        
        // Submit order
        const response = await fetch('/checkout/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Redirect to success page
            window.location.href = `/checkout/success/${data.order_id}`;
        } else {
            throw new Error(data.message || 'Failed to place order');
        }
    } catch (error) {
        console.error('Order error:', error);
        alert('Failed to place order: ' + error.message);
        placeOrderBtn.disabled = false;
        orderSpinner.classList.add('hidden');
    }
}

function getFormData(form) {
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    return data;
}

function getPaymentData() {
    const activeTab = document.querySelector('.payment-tab.active');
    const tabName = activeTab ? activeTab.dataset.tab : 'credit-card';
    
    let paymentData = {
        method: tabName
    };
    
    if (tabName === 'credit-card') {
        const paymentForm = document.getElementById('paymentForm');
        paymentData.details = getFormData(paymentForm);
    }
    
    return paymentData;
}

function getShippingCost() {
    const shippingText = document.getElementById('shippingCost').textContent;
    return shippingText === 'FREE' ? 0 : parseFloat(shippingText.replace('$', '')) || 0;
}

function getDiscountAmount() {
    const storedDiscount = sessionStorage.getItem('promoDiscount');
    if (storedDiscount) {
        const discountValue = JSON.parse(storedDiscount);
        if (typeof discountValue === 'number') {
            return {{ $subtotal }} * discountValue;
        }
    }
    return 0;
}

function getTotalAmount() {
    const totalText = document.getElementById('orderTotal').textContent;
    return parseFloat(totalText.replace('$', '')) || 0;
}
</script>
@endpush