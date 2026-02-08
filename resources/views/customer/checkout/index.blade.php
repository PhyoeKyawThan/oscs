@extends('layouts.template')
@section('content')
    <div id="checkoutPage" class="page">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold">Checkout</h2>
                <p class="text-gray-500">Complete your purchase</p>
            </div>

            @if($cartItems && count($cartItems) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <!-- Delivery Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                            <h3 class="text-xl font-bold mb-4">Delivery Information</h3>

                            <form id="checkoutForm">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Full Name *
                                        </label>
                                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Email *
                                        </label>
                                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Phone *
                                        </label>
                                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Delivery Method *
                                        </label>
                                        <select name="delivery_method"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                            <option value="standard">Standard Delivery (3000 MMKS)</option>
                                            <option value="express">Express Delivery (4000 MMKS)</option>
                                            <option value="pickup">Store Pickup (FREE)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Address *
                                    </label>
                                    <input type="text" name="address"
                                        value="{{ old('address', auth()->user()->address ?? '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            City *
                                        </label>
                                        <input type="text" name="city" value="{{ old('city', auth()->user()->city ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            State *
                                        </label>
                                        <input type="text" name="state" value="{{ old('state', auth()->user()->state ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                            Postal Code *
                                        </label>
                                        <input type="text" name="postal_code"
                                            value="{{ old('postal_code', auth()->user()->zip_code ?? '') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 dark:text-gray-300 mb-2 text-sm font-medium">
                                        Delivery Instructions (Optional)
                                    </label>
                                    <textarea name="delivery_instructions" rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="Special instructions for delivery..."></textarea>
                                </div>
                                <div class="mb-6">
                                    <h2 class="text-gray-800 font-bold">Payment ( currently only KBZ pay available )</h2>
                                    <div class="flex flex-col justify-center items-center">
                                        <img src="{{ asset('./storage/assets/paymentqr.png') }}" alt="">
                                        <label for="payment-proof"
                                            class="p-2 rounded-lg bg-blue-700 text-gray-100 font-semibold cursor-pointer hover:bg-blue-400 transition-all-300">
                                            <i class="fa-solid fa-money-bills pr-2"></i>Make Payment: </button>
                                            <input type="file" name="payment_proof" id="payment-proof">
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sticky top-24">
                            <h3 class="text-xl font-bold mb-4">Order Summary</h3>

                            <!-- Order Items -->
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center pb-3 border-b border-gray-100">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <h4 class="font-medium text-sm">{{ $item['name'] }}</h4>
                                            <p class="text-gray-500 text-xs">Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                        <div class="font-medium">
                                            {{ number_format($item['price'] * $item['quantity'], 2) }} MMKS
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="space-y-2 border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>{{ number_format($subtotal, 2) }} MMKS</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span id="shippingCost">$5.99</span>
                                </div>
                                {{-- <div class="flex justify-between">
                                    <span>Tax (10%)</span>
                                    <span>${{ number_format($tax, 2) }}</span>
                                </div> --}}
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-4 mt-4">
                                <span>Total</span>
                                <span id="orderTotal">${{ number_format($total, 2) }}</span>
                            </div>

                            <!-- Terms & Place Order -->
                            <div class="mt-6">
                                <label class="flex items-start mb-4">
                                    <input type="checkbox" name="terms" class="mt-1 mr-2" required>
                                    <span class="text-sm text-gray-600">
                                        I agree to the terms and conditions
                                    </span>
                                </label>

                                <button type="button" id="placeOrderBtn"
                                    class="w-full py-3 bg-primary text-white rounded-lg font-bold hover:bg-blue-700 transition-all-300">
                                    Place Order
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-3">
                                    <i class="fas fa-lock mr-1"></i>
                                    Secure checkout
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-12 bg-gray-100 rounded-xl">
                    <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
                    <a href="{{ route('products.index') }}"
                        class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Update shipping cost when delivery method changes
            document.querySelector('select[name="delivery_method"]').addEventListener('change', function () {
                updateShippingCost();
            });

            // Place order button
            document.getElementById('placeOrderBtn').addEventListener('click', placeOrder);
        });

        function updateShippingCost() {
            const method = document.querySelector('select[name="delivery_method"]').value;
            let shipping = 0;

            switch (method) {
                case 'standard': shipping = 3000.00; break;
                case 'express': shipping = 4000.00; break;
                case 'pickup': shipping = 0; break;
            }

            document.getElementById('shippingCost').textContent =
                shipping === 0 ? 'FREE' : `${shipping.toFixed(2)} MMKS`;

            // Recalculate total
            const subtotal = {{ $subtotal }};
            // const tax = {{ $tax }};
            const total = subtotal + shipping;
            document.getElementById('orderTotal').textContent = `${total.toFixed(2)} MMKS`;
        }

        async function placeOrder() {
            const btn = document.getElementById('placeOrderBtn');
            const form = document.getElementById('checkoutForm');

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validate terms
            if (!document.querySelector('input[name="terms"]').checked) {
                alert('Please agree to the terms and conditions.');
                return;
            }

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

            try {
                // Prepare form data
                const formData = new FormData(form);

                // Send request to store method
                const response = await fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to order confirmation
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.message || 'Failed to place order');
                }
            } catch (error) {
                console.error('Order error:', error);
                alert(error.message || 'Something went wrong. Please try again.');
                btn.disabled = false;
                btn.innerHTML = 'Place Order';
            }
        }
    </script>
@endpush