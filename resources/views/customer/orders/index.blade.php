@extends('layouts.tempalte')
@section('content')
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
@endsection