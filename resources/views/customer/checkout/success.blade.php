@extends('layouts.template')
@section('content')
<div id="checkoutSuccessPage" class="page">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <div class="w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            <h2 class="text-3xl font-bold mb-2">Order Confirmed!</h2>
            <p class="text-gray-500 dark:text-gray-400">Thank you for your purchase</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 mb-8">
            <div class="mb-6">
                <h3 class="text-xl font-bold mb-2">Order Details</h3>
                <p class="text-gray-600 dark:text-gray-400">Order #{{ $order->order_number }}</p>
            </div>
            
            <div class="space-y-4 mb-8">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Order Date</span>
                    <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                    <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Shipping Method</span>
                    <span class="font-medium">{{ ucfirst(str_replace('-', ' ', $order->shipping_method)) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span>Total Amount</span>
                    <span class="text-primary">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
            
            <div class="mb-8">
                <h4 class="font-bold mb-3">What's Next?</h4>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <p class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-primary"></i>
                        You'll receive an order confirmation email shortly
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-shipping-fast mr-2 text-primary"></i>
                        Shipping updates will be sent to your email
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-question-circle mr-2 text-primary"></i>
                        Need help? Contact our customer support
                    </p>
                </div>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('customer.orders') }}" 
                   class="block w-full py-3 bg-primary text-white rounded-lg font-bold hover:bg-blue-700 transition-all-300">
                    View Order Details
                </a>
                <a href="{{ route('products.index') }}" 
                   class="block w-full py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all-300">
                    Continue Shopping
                </a>
            </div>
        </div>
        
        <div class="text-sm text-gray-500 dark:text-gray-400">
            <p>Questions about your order? <a href="#" class="text-primary hover:underline">Contact Support</a></p>
        </div>
    </div>
</div>
@endsection