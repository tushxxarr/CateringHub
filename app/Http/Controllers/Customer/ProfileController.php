<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        // Get the authenticated user's customer profile
        $profile = Auth::user()->customerProfile;

        // Get order statistics
        $customerId = $profile->id;

        // Total orders count
        $orderCount = Order::where('customer_id', $customerId)->count();

        // Completed orders count
        $completedOrderCount = Order::where('customer_id', $customerId)
            ->where('status', 'completed')
            ->count();

        // Pending orders count
        $pendingOrderCount = Order::where('customer_id', $customerId)
            ->where('status', 'pending')
            ->count();

        // Get recent activities (last 5 orders)
        $recentOrders = Order::where('customer_id', $customerId)
            ->with('merchant')
            ->latest()
            ->get();

        $recentActivities = [];

        foreach ($recentOrders as $order) {
            $activity = [
                'description' => '',
                'time' => Carbon::parse($order->created_at)->diffForHumans(),
                'icon' => '',
                'color' => ''
            ];

            switch ($order->status) {
                case 'pending':
                    $activity['description'] = "Placed order #{$order->order_number} with {$order->merchant->business_name}";
                    $activity['icon'] = 'shopping-cart';
                    $activity['color'] = 'warning';
                    break;
                case 'processing':
                    $activity['description'] = "Order #{$order->order_number} is being processed";
                    $activity['icon'] = 'cog';
                    $activity['color'] = 'info';
                    break;
                case 'completed':
                    $activity['description'] = "Completed order #{$order->order_number}";
                    $activity['icon'] = 'check-circle';
                    $activity['color'] = 'success';
                    break;
                case 'cancelled':
                    $activity['description'] = "Cancelled order #{$order->order_number}";
                    $activity['icon'] = 'times-circle';
                    $activity['color'] = 'danger';
                    break;
                default:
                    $activity['description'] = "Updated order #{$order->order_number}";
                    $activity['icon'] = 'sync';
                    $activity['color'] = 'primary';
            }

            $recentActivities[] = $activity;
        }

        return view('customer.profile.show', compact(
            'profile',
            'orderCount',
            'completedOrderCount',
            'pendingOrderCount',
            'recentActivities'
        ));
    }

    public function edit()
    {
        $profile = Auth::user()->customerProfile;
        return view('customer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $profile = Auth::user()->customerProfile;
        $profile->company_name = $request->company_name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->description = $request->description;
        $profile->save();

        return redirect()->route('customer.profile.show')->with('success', 'Profile updated successfully.');
    }
}
