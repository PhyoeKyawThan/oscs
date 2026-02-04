<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4361ee;
        }
        
        .company-info h1 {
            color: #4361ee;
            margin: 0 0 10px 0;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .details {
            margin-bottom: 30px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #4361ee;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: bold;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            float: right;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #4361ee;
            border-bottom: none;
            padding-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        
        .footer {
            margin-top: 100px;
            padding-top: 20px;
            border-top: 2px solid #4361ee;
            text-align: center;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending { background: #ffc107; color: #000; }
        .status-processing { background: #0dcaf0; color: #000; }
        .status-shipped { background: #4361ee; color: #fff; }
        .status-delivered { background: #198754; color: #fff; }
        .status-completed { background: #198754; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }
        
        .payment-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .payment-paid { background: #198754; color: #fff; }
        .payment-pending { background: #ffc107; color: #000; }
        .payment-failed { background: #dc3545; color: #fff; }
        .payment-refunded { background: #6c757d; color: #fff; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name') }}</h1>
                <p>123 Business Street<br>
                   City, State 12345<br>
                   Phone: (123) 456-7890<br>
                   Email: info@{{ config('app.name') }}.com</p>
            </div>
            
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ strtolower($order->status) }}">
                        {{ $order->status }}
                    </span>
                </p>
                <p><strong>Payment:</strong> 
                    <span class="payment-badge payment-{{ strtolower($order->payment_status ?? 'pending') }}">
                        {{ $order->payment_status ?? 'Pending' }}
                    </span>
                </p>
            </div>
        </div>
        
        <!-- Customer Details -->
        <div class="details">
            <div class="details-grid">
                <div>
                    <h3 class="section-title">Bill To</h3>
                    <p>
                        <strong>{{ $deliveryInfo['name'] ?? 'Customer' }}</strong><br>
                        {{ $deliveryInfo['email'] ?? '' }}<br>
                        {{ $deliveryInfo['phone'] ?? '' }}
                    </p>
                </div>
                
                <div>
                    <h3 class="section-title">Ship To</h3>
                    <p>
                        {{ $deliveryInfo['address'] ?? '' }}<br>
                        {{ $deliveryInfo['city'] ?? '' }}, {{ $deliveryInfo['state'] ?? '' }}<br>
                        {{ $deliveryInfo['postal_code'] ?? '' }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="section">
            <h3 class="section-title">Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
            </div>
            
            @php
                $deliveryInfo = is_array($order->delivery_information) 
                    ? $order->delivery_information 
                    : json_decode($order->delivery_information, true);
                $shipping = $deliveryInfo['method'] ?? '';
                $shippingCost = 0;
                if ($shipping == 'express') {
                    $shippingCost = 12.99;
                } elseif ($shipping == 'standard') {
                    $shippingCost = 5.99;
                }
            @endphp
            
            <div class="total-row">
                <span>Shipping:</span>
                <span>${{ number_format($shippingCost, 2) }}</span>
            </div>
            
            @if(isset($order->tax_amount) && $order->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>${{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row grand-total">
                <span>Total:</span>
                <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
        
        <div style="clear: both;"></div>
        
        <!-- Payment Information -->
        <div class="section">
            <h3 class="section-title">Payment Information</h3>
            <p>
                <strong>Payment Method:</strong> {{ $order->payment_method ?? 'Cash on Delivery' }}<br>
                <strong>Payment Status:</strong> {{ $order->payment_status ?? 'Pending' }}<br>
                @if($order->payment_reference)
                    <strong>Transaction ID:</strong> {{ $order->payment_reference }}
                @endif
            </p>
        </div>
        
        <!-- Notes -->
        @if($order->notes)
        <div class="section">
            <h3 class="section-title">Customer Notes</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ config('app.name') }} | Phone: (123) 456-7890 | Email: info@{{ config('app.name') }}.com</p>
            <p>This is a computer-generated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>