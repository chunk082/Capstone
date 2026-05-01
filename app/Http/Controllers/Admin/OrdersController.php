<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', "%{$request->search}%")
                  ->orWhere('id', $request->search);
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'product']);

        return view('admin.orders-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'status' => 'Shipped'
        ]);

        return back()->with('success', 'Tracking number updated.');
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Order cancelled.');
    }
}