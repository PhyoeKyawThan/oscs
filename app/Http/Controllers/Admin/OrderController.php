<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function index(Request $request)
    {
        $query = Orders::with('user');

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
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Orders::with(['user', 'items.product'])
            ->findOrFail($id);

        $deliveryInfo = $order->delivery_information;

        return view('admin.orders.show', compact('order', 'deliveryInfo'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Shipping,Completed,Cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $order = Orders::findOrFail($id);

        // Update status
        $order->status = $request->status;
        // Add status history
        // $statusHistory = $order->status_history ?? [];
        // $statusHistory[] = [
        //     'status' => $request->status,
        //     'notes' => $request->notes,
        //     'changed_at' => now()->toDateTimeString(),
        //     'changed_by' => auth('admin')->user()->name
        // ];
        
        // $order->status_history = $statusHistory;
        $order->save();

        // Here you can add notification logic (email to customer, etc.)

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function invoice($request, $id)
    {
        $order = Orders::with(['user', 'items.product'])
            ->findOrFail($id);

        $deliveryInfo = $order->delivery_information;

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order', 'deliveryInfo'));
        
        if ($request->has('download')) {
            return $pdf->download('invoice-' . $order->order_number . '.pdf');
        }
        
        return $pdf->stream();
    }

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);

        // Only allow deletion of cancelled orders
        if ($order->status !== 'Cancelled') {
            return redirect()->back()->with('error', 'Only cancelled orders can be deleted.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function export(Request $request)
    {
        // Implementation for exporting orders to CSV/Excel
        // You can use Laravel Excel package here
    }
}