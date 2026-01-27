<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('customer.login.index')
                ->with('error', 'Please login to proceed to checkout.');
        }
        
        // Get cart items from session
        $cartItems = session()->get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add items to checkout.');
        }
        
        // Calculate totals
        $subtotal = $this->calculateSubtotal($cartItems);
        $tax = $this->calculateTax($subtotal);
        $shipping = 5.99; // Default shipping
        $discount = 0; // Calculate discount if any
        $total = $subtotal + $tax + $shipping - $discount;
        
        return view('customer.checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'shipping',
            'discount',
            'total'
        ));
    }
    
    public function process(Request $request)
    {
        // Validate the request
        $request->validate([
            'delivery.first_name' => 'required|string|max:255',
            'delivery.last_name' => 'required|string|max:255',
            'delivery.email' => 'required|email',
            'delivery.phone' => 'required|string|max:20',
            'delivery.address' => 'required|string',
            'delivery.city' => 'required|string',
            'delivery.state' => 'required|string',
            'delivery.zip_code' => 'required|string',
            'delivery.country' => 'required|string',
            'payment.method' => 'required|in:credit-card,paypal,cod',
        ]);
        
        // Process payment based on method
        $paymentMethod = $request->input('payment.method');
        
        try {
            // Create order
            $order = $this->createOrder($request);
            
            // Process payment
            $paymentResult = $this->processPayment($paymentMethod, $request);
            
            if ($paymentResult['success']) {
                // Clear cart
                session()->forget('cart');
                
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Order placed successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function success($orderId)
    {
        $order = Orders::with('items')->findOrFail($orderId);
        
        // Verify that the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('customer.checkout.success', compact('order'));
    }
    
    private function calculateSubtotal($cartItems)
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }
    
    private function calculateTax($subtotal)
    {
        // Example: 8.25% tax rate
        return $subtotal * 0.0825;
    }
    
    private function createOrder(Request $request)
    {
        $cartItems = session()->get('cart', []);
        
        $order = Orders::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'status' => 'pending',
            'subtotal' => $this->calculateSubtotal($cartItems),
            'tax' => $this->calculateTax($this->calculateSubtotal($cartItems)),
            'shipping' => $request->input('delivery.delivery_method') === 'pickup' ? 0 : 
                         ($request->input('delivery.delivery_method') === 'express' ? 12.99 : 5.99),
            'total' => $request->input('totals.total'),
            'payment_method' => $request->input('payment.method'),
            'payment_status' => 'pending',
            'shipping_method' => $request->input('delivery.delivery_method'),
            'shipping_address' => json_encode($request->input('delivery')),
            'notes' => $request->input('delivery.delivery_instructions'),
        ]);
        
        // Create order items
        foreach ($cartItems as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }
        
        return $order;
    }
    
    private function processPayment($method, Request $request)
    {
        // In a real application, you would integrate with payment gateways
        // This is a simplified example
        
        switch ($method) {
            case 'credit-card':
                // Process credit card payment (integrate with Stripe, PayPal, etc.)
                return ['success' => true, 'message' => 'Payment processed'];
                
            case 'paypal':
                // Redirect to PayPal
                return ['success' => true, 'message' => 'Redirect to PayPal'];
                
            case 'cod':
                // Cash on delivery - no payment processing needed
                return ['success' => true, 'message' => 'Cash on delivery selected'];
                
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }
}