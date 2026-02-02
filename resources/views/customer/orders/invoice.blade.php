<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }
            .page-break {
                page-break-before: always;
            }
        }
        @page {
            margin: 20px;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Print Button -->
        <div class="no-print mb-6 flex justify-end">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i>
                Print Invoice
            </button>
        </div>

        <!-- Invoice Container -->
        <div class="invoice-container bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-gray-100 pb-6 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">INVOICE</h1>
                    <p class="text-gray-500 mt-1">Order #{{ $order->order_number }}</p>
                </div>
                
                <div class="mt-4 md:mt-0 text-right">
                    <div class="inline-flex items-center px-4 py-2 rounded-lg {{ $order->status_color }}">
                        <span class="font-semibold">{{ $order->status }}</span>
                    </div>
                    <p class="text-gray-500 mt-2">Date: {{ $order->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            <!-- Company & Customer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">From</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-bold text-xl text-gray-900">Your Store Name</p>
                        <p class="text-gray-600 mt-2">123 Business Street</p>
                        <p class="text-gray-600">City, State 12345</p>
                        <p class="text-gray-600">Country</p>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-phone mr-2"></i>+1 (123) 456-7890
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-envelope mr-2"></i>info@yourstore.com
                        </p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Bill To</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-gray-600 mt-2">{{ $order->user->email }}</p>
                        
                        @if($deliveryInfo && is_array($deliveryInfo))
                            @if(!empty($deliveryInfo['phone']))
                                <p class="text-gray-600">
                                    <i class="fas fa-phone mr-2"></i>{{ $deliveryInfo['phone'] }}
                                </p>
                            @endif
                            
                            @if(!empty($deliveryInfo['address']))
                                <div class="mt-2">
                                    <p class="font-medium text-gray-700 mb-1">Delivery Address:</p>
                                    <p class="text-gray-600">{{ $deliveryInfo['address'] }}</p>
                                    @if(!empty($deliveryInfo['city']))
                                        <p class="text-gray-600">{{ $deliveryInfo['city'] }}, 
                                            @if(!empty($deliveryInfo['state'])){{ $deliveryInfo['state'] }}, @endif
                                            @if(!empty($deliveryInfo['zip_code'])){{ $deliveryInfo['zip_code'] }}@endif
                                        </p>
                                    @endif
                                    @if(!empty($deliveryInfo['country']))
                                        <p class="text-gray-600">{{ $deliveryInfo['country'] }}</p>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items Table -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left py-3 px-4 border-b font-semibold text-gray-700">Item</th>
                                <th class="text-left py-3 px-4 border-b font-semibold text-gray-700">Price</th>
                                <th class="text-left py-3 px-4 border-b font-semibold text-gray-700">Quantity</th>
                                <th class="text-left py-3 px-4 border-b font-semibold text-gray-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Product' }}</p>
                                            @if($item->product && $item->product->sku)
                                                <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-700">
                                    ₹{{ number_format($item->price, 2) }}
                                </td>
                                <td class="py-4 px-4 text-gray-700">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-900">
                                    ₹{{ number_format($item->price * $item->quantity, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment & Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Payment Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium text-gray-900">
                                @if($deliveryInfo && !empty($deliveryInfo['payment_method']))
                                    {{ ucfirst($deliveryInfo['payment_method']) }}
                                @else
                                    Cash on Delivery
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="font-medium text-green-600">Paid</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-medium text-gray-900">{{ strtoupper(Str::random(10)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium text-gray-900">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium text-gray-900">
                                @if($order->total_amount > 500)
                                    <span class="text-green-600">FREE</span>
                                @else
                                    ₹50.00
                                @endif
                            </span>
                        </div>
                        @if($order->total_amount <= 500)
                        <div class="flex justify-between mb-3 border-b pb-3">
                            <span class="text-gray-600">Shipping Fee:</span>
                            <span class="font-medium text-gray-900">₹50.00</span>
                        </div>
                        @endif
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Tax ({{ config('invoice.tax_rate', 18) }}%):</span>
                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($order->total_amount * (config('invoice.tax_rate', 18) / 100), 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-3 border-t">
                            <span class="text-gray-900">Grand Total:</span>
                            <span class="text-blue-600">
                                @php
                                    $shipping = $order->total_amount > 500 ? 0 : 50;
                                    $tax = $order->total_amount * (config('invoice.tax_rate', 18) / 100);
                                    $grandTotal = $order->total_amount + $shipping + $tax;
                                @endphp
                                ₹{{ number_format($grandTotal, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="mt-10 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Terms & Conditions</h3>
                <div class="text-gray-600 text-sm">
                    <p class="mb-2">1. Goods once sold will not be taken back or exchanged.</p>
                    <p class="mb-2">2. All disputes are subject to jurisdiction of the courts in your city.</p>
                    <p class="mb-2">3. Delivery within 5-7 business days.</p>
                    <p class="mb-2">4. For any queries, contact our customer support at support@yourstore.com</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-10 pt-6 border-t border-gray-200 text-center">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-gray-700 font-semibold">Thank you for your business!</p>
                        <p class="text-gray-500 text-sm">We appreciate your trust in us.</p>
                    </div>
                    <div class="text-gray-500 text-sm">
                        <p>Invoice generated on: {{ now()->format('F d, Y \a\t h:i A') }}</p>
                        <p>This is a computer-generated invoice. No signature required.</p>
                    </div>
                </div>
                
                <!-- QR Code Placeholder -->
                <div class="mt-6 flex flex-col items-center">
                    <div class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded-lg mb-2">
                        <i class="fas fa-qrcode text-gray-400 text-4xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Scan for payment verification</p>
                </div>
            </div>
        </div>

        <!-- Download Options -->
        <div class="no-print mt-8 flex flex-wrap gap-4 justify-center">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i>
                Print Invoice
            </button>
            {{-- <button onclick="downloadPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-file-pdf"></i>
                Download as PDF
            </button> --}}
            <a href="{{ route('customer.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to Orders
            </a>
        </div>
    </div>

    <script>
        function downloadPDF() {
            alert('PDF download functionality requires a backend PDF generator like DomPDF.');
            // For actual PDF generation, you would need to:
            // 1. Install dompdf: composer require barryvdh/laravel-dompdf
            // 2. Create a PDF download route
            // 3. Use: return \PDF::loadView('customer.orders.invoice', compact('order', 'deliveryInfo'))->download('invoice-{{ $order->order_number }}.pdf');
        }

        // Auto-print option
        @if(request()->has('print'))
            window.addEventListener('load', function() {
                window.print();
            });
        @endif
    </script>
</body>
</html>