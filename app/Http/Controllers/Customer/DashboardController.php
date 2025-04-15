<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customerProfile;

        // Order counts
        $pendingOrdersCount = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->count();

        $completedOrdersCount = Order::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->count();

        // Unpaid invoices count
        $unpaidInvoicesCount = Invoice::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'pending')
            ->count();

        // Cart items count
        $cart = Session::get('cart', []);
        $cartItemsCount = count($cart);

        // Recent orders with merchant relationship
        $recentOrders = Order::where('customer_id', $customer->id)
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Popular merchants based on order count
        $popularMerchants = MerchantProfile::withCount(['orders' => function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        }])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'pendingOrdersCount',
            'completedOrdersCount',
            'unpaidInvoicesCount',
            'cartItemsCount',
            'recentOrders',
            'popularMerchants'
        ));
    }
}
