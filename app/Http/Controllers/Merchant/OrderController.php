<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchantProfile;
        $orders = Order::where('merchant_id', $merchant->id)
            ->with('customer', 'orderItems.foodItem')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('merchant.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('customer', 'orderItems.foodItem', 'invoice');

        return view('merchant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('merchant.orders.show', $order)->with('success', 'Order status updated successfully.');
    }
}
