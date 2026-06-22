<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the business.
     */
    public function index()
    {
        $orders = auth()->user()->businessOrders()
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('business.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the business
        if ($order->business_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['user', 'items.product']);

        return view('business.orders.show', compact('order'));
    }
}
