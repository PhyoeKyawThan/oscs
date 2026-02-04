<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // List all orders for the authenticated user
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get orders with pagination
        $orders = Orders::where('user_id', $user->id)
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.orders.index', compact('orders'));
        // Changed from 'order' to 'orders'
    }

    // Show order details
    public function show($orderNumber)
    {
        $user = Auth::user();

        $order = Orders::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.product'])
            ->firstOrFail();

        // Decode delivery information
        // $deliveryInfo = json_decode($order->delivery_information, true);
        $deliveryInfo = $order->delivery_information;

        return view('customer.orders.show', compact('order', 'deliveryInfo'));
    }

    // Cancel order (if allowed)
    public function cancel($orderNumber)
    {
        $user = Auth::user();

        $order = Orders::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if order can be cancelled
        if ($order->status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage.'
            ], 400);
        }

        try {
            $order->status = 'Cancelled';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            \Log::error('Order cancellation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order. Please try again.'
            ], 500);
        }
    }

    // Track order status
    public function track($orderNumber)
    {
        $user = Auth::user();

        $order = Orders::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->format('M d, Y'),
                'status_updated_at' => $order->updated_at->format('M d, Y H:i'),
                'estimated_delivery' => $this->getEstimatedDelivery($order)
            ]
        ]);
    }

    // Get order statistics for dashboard
    public function getOrderStats()
    {
        $user = Auth::user();

        $stats = [
            'total_orders' => Orders::where('user_id', $user->id)->count(),
            'pending_orders' => Orders::where('user_id', $user->id)
                ->where('status', 'Pending')->count(),
            'delivery_orders' => Orders::where('user_id', $user->id)
                ->where('status', 'On Delivery')->count(),
            'completed_orders' => Orders::where('user_id', $user->id)
                ->where('status', 'Completed')->count(),
            'total_spent' => Orders::where('user_id', $user->id)
                ->where('status', 'Completed')
                ->sum('total_amount')
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    // Reorder functionality
    public function reorder($orderNumber)
    {
        $user = Auth::user();

        $order = Orders::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->firstOrFail();

        // Check if all products are still available
        $unavailableProducts = [];
        foreach ($order->items as $item) {
            if (!$item->product || !$item->product->is_available) {
                $unavailableProducts[] = $item->product->name ?? 'Unknown Product';
            }
        }

        if (!empty($unavailableProducts)) {
            return response()->json([
                'success' => false,
                'message' => 'Some products are no longer available.',
                'unavailable_products' => $unavailableProducts
            ], 400);
        }

        // Create cart session or return data for frontend
        $cartItems = [];
        foreach ($order->items as $item) {
            $cartItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product' => $item->product
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Order items added to cart.',
            'cart_items' => $cartItems,
            'redirect' => route('cart.index')
        ]);
    }


    public function invoice($orderNumber)
    {
        $user = Auth::user();

        $order = Orders::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        $deliveryInfo = $order->delivery_information;

        // Return view for browser
        if (!request()->has('download')) {
            return view('customer.orders.invoice', compact('order', 'deliveryInfo'));
        }

        // Return PDF for download
        $pdf = Pdf::loadView('customer.orders.invoice', compact('order', 'deliveryInfo'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // Get order history with filters
    public function getOrderHistory(Request $request)
    {
        $user = Auth::user();

        $query = Orders::where('user_id', $user->id)
            ->withCount('items');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'filters' => $request->all()
        ]);
    }

    // Helper method for estimated delivery
    private function getEstimatedDelivery($order)
    {
        $statusDays = [
            'Pending' => 7,
            'On Delivery' => 2,
            'Completed' => 0
        ];

        $days = $statusDays[$order->status] ?? 7;

        if ($days > 0) {
            $deliveryDate = $order->created_at->addDays($days);
            return $deliveryDate->format('M d, Y');
        }

        return 'Delivered';
    }

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'delivery_method' => 'required',
        ]);

        // 2. Get Cart Data (Assuming it's stored in Session)
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        try {
            return DB::transaction(function () use ($request, $cart) {
                // Calculate Totals (Don't trust frontend totals, recalculate here)
                $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                $shipping = $request->delivery_method === 'pickup' ? 0 : ($request->delivery_method === 'express' ? 12.99 : 5.99);
                $total = $subtotal + $shipping; // Add tax calculation if needed

                // 3. Create Order
                $order = Orders::create([
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'user_id' => Auth::id(),
                    'status' => 'Pending',
                    'total_amount' => $total,
                    'delivery_information' => [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'postal_code' => $request->postal_code,
                        'method' => $request->delivery_method,
                        'instructions' => $request->delivery_instructions
                    ],
                    'payment_method' => $request->payment_method ?? 'COD',
                    'payment_status' => 'Pending'
                ]);

                // 4. Create Order Items
                foreach ($cart as $id => $details) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'price' => $details['price']
                    ]);
                }

                // 5. Clear Cart
                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_number' => $order->order_number,
                    'redirect' => route('customer.orders.show', $order->order_number)
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}