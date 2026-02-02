@extends('layouts.template')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-4 text-left">Order #</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Items</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Total</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-t">
                    <td class="p-4 font-medium">
                        {{ $order->order_number }}
                    </td>
                    <td class="p-4">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="p-4 text-center">
                        {{ $order->items_count }}
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-sm {{ $order->getStatusColorAttribute() }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold">
                        ₹{{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('customer.orders.show', $order->order_number) }}"
                           class="text-primary hover:underline">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
