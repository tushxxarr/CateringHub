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

        // Pencarian berdasarkan kata kunci
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('company_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereHas('foodItems.category', function ($q) use ($request) {
                $q->whereIn('food_categories.id', $request->categories);
            });
        }

        // Pengurutan (sorting)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('company_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('company_name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('company_name', 'asc');
            }
        } else {
            $query->orderBy('company_name', 'asc');
        }

        $merchants = $query->paginate(10)->withQueryString();

        // Ambil semua kategori makanan untuk filter
        $categories = FoodCategory::all();

        return view('customer.merchants.index', compact('merchants', 'categories'));
    }

    public function show(Request $request, MerchantProfile $merchant)
    {
        $categories = FoodCategory::whereHas('foodItems', function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id)
                ->where('is_available', true);
        })->get();

        $foodItemsQuery = $merchant->foodItems()->where('is_available', true);

        // Filter by category if provided
        if ($request->has('category')) {
            $categoryId = $request->get('category');
            $foodItemsQuery->where('category_id', $categoryId);
        }

        $foodItems = $foodItemsQuery->get()->groupBy('category_id');

        return view('customer.merchants.show', compact('merchant', 'categories', 'foodItems'));
    }
}
