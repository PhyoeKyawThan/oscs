<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by registration date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with(['orders' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);

        // Get customer statistics
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('status', 'Completed')->sum('total_amount'),
            'avg_order_value' => $customer->orders()->where('status', 'Completed')->avg('total_amount'),
            'last_order_date' => $customer->orders()->latest()->first()->created_at ?? null,
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('password_confirmation');
        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now(); // Auto verify admin-created accounts

        User::create($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('password', 'password_confirmation');

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        // Check if customer has orders
        if ($customer->orders()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete customer with existing orders.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function getCustomerOrders($id)
    {
        $customer = User::findOrFail($id);
        
        $orders = $customer->orders()
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.customers.orders', compact('customer', 'orders'));
    }
}