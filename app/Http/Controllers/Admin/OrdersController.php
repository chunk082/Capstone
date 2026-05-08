<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /* Display a listing of orders with optional filtering and search. */
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

    /* Display the details of a specific order. */
    public function show(Order $order)
    {
        $order->load(['user', 'product']);

        return view('admin.orders-show', compact('order'));
    }

    /* Update the status of an order. */
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

    /* Update the tracking number and set status to Shipped. */
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

    /* Cancel an order by setting its status to Cancelled. */
    public function cancel(Order $order)
    {
        $order->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Order cancelled.');
    }
}