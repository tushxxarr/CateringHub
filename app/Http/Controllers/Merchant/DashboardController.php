<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\FoodItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchantProfile;

        // Jumlah pesanan
        $totalOrders = Order::where('merchant_id', $merchant->id)->count();

        // Jumlah pesanan pending
        $pendingOrdersCount = Order::where('merchant_id', $merchant->id)
            ->where('status', 'pending')
            ->count();

        // Jumlah pesanan selesai
        $completedOrders = Order::where('merchant_id', $merchant->id)
            ->where('status', 'completed')
            ->count();

        // Jumlah produk yang dimiliki merchant
        $foodItemsCount = $merchant->foodItems()->count();

        // Pesanan terbaru (5 terakhir)
        $recentOrders = Order::with('customer.user')
            ->where('merchant_id', $merchant->id)
            ->latest()
            ->take(5)
            ->get();

        // Total revenue dari invoice yang sudah dibayar
        $totalRevenue = Order::where('merchant_id', $merchant->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Jumlah invoice yang belum dibayar
        $unpaidInvoicesCount = Invoice::where('merchant_id', $merchant->id)
            ->where('status', 'pending')
            ->count();

        // Item terlaris
        $topSellingItems = FoodItem::withCount([
            'orderItems as sales_count' => function ($query) {
                $query->select(DB::raw("SUM(quantity)"));
            }
        ])
            ->where('merchant_id', $merchant->id)
            ->orderByDesc('sales_count')
            ->take(5)
            ->get();

        return view('merchant.dashboard', compact(
            'totalOrders',
            'pendingOrdersCount',
            'completedOrders',
            'foodItemsCount',
            'recentOrders',
            'totalRevenue',
            'unpaidInvoicesCount',
            'topSellingItems'
        ));
    }
}
