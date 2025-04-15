<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->merchantProfile;

        // Get food items count
        $foodItemsCount = FoodItem::where('merchant_id', $profile->id)->count();

        // Get total orders
        $totalOrders = Order::where('merchant_id', $profile->id)->count();

        // Get total revenue
        $totalRevenue = Order::where('merchant_id', $profile->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Get recent orders
        $recentOrders = Order::where('merchant_id', $profile->id)
            ->with(['customer.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('merchant.profile.show', compact(
            'profile',
            'foodItemsCount',
            'totalOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }

    public function edit()
    {
        $profile = Auth::user()->merchantProfile;
        return view('merchant.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $profile = Auth::user()->merchantProfile;
        $profile->company_name = $request->company_name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->description = $request->description;

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $profile->logo = $request->file('logo')->store('logos', 'public');
        }

        $profile->save();

        return redirect()->route('merchant.profile.show')->with('success', 'Profile updated successfully.');
    }
}
