<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customerProfile;
        $totalOrders = Order::where('customer_id', $customer->id)->count();
        $pendingOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->count();
        $completedOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->count();

        $latestOrders = Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'latestOrders'
        ));
    }
}
