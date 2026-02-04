<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function index()
    {
        // Get statistics for dashboard
        $stats = [
            'total_orders' => Orders::count(),
            'pending_orders' => Orders::where('status', 'Pending')->count(),
            'total_revenue' => Orders::where('status', 'Completed')->sum('total_amount'),
            'total_products' => Products::count(),
            'total_customers' => User::count(),
            'total_categories' => Categories::count(),
            'low_stock_products' => Products::where('stock', '<', 10)->count(),
        ];

        // Recent orders
        $recentOrders = Orders::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top selling products
        $topProducts = DB::table('order_item')
            ->join('products', 'order_item.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_item.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Sales chart data (last 30 days)
        $salesData = Orders::where('status', 'Completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders', 'topProducts', 'salesData'));
    }
}