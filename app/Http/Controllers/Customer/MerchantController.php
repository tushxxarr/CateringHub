<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $query = MerchantProfile::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('company_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $merchants = $query->paginate(10);
        return view('customer.merchants.index', compact('merchants'));
    }

    public function show(MerchantProfile $merchant)
    {
        $categories = FoodCategory::whereHas('foodItems', function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id)
                ->where('is_available', true);
        })->get();

        $foodItems = $merchant->foodItems()
            ->where('is_available', true)
            ->get()
            ->groupBy('category_id');

        return view('customer.merchants.show', compact('merchant', 'categories', 'foodItems'));
    }
}
