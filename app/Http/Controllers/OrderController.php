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
}