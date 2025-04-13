<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchantProfile;
        $foodItems = $merchant->foodItems()->with('category')->paginate(10);
        return view('merchant.food-items.index', compact('foodItems'));
    }

    public function create()
    {
        $categories = FoodCategory::all();
        return view('merchant.food-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:food_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $foodItem = new FoodItem();
        $foodItem->merchant_id = Auth::user()->merchantProfile->id;
        $foodItem->name = $request->name;
        $foodItem->category_id = $request->category_id;
        $foodItem->description = $request->description;
        $foodItem->price = $request->price;
        $foodItem->is_available = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $foodItem->image = $request->file('image')->store('food-items', 'public');
        }

        $foodItem->save();

        return redirect()->route('merchant.food-items.index')->with('success', 'Food item created successfully.');
    }

    public function show(FoodItem $foodItem)
    {
        $this->authorize('view', $foodItem);
        return view('merchant.food-items.show', compact('foodItem'));
    }

    public function edit(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $categories = FoodCategory::all();
        return view('merchant.food-items.edit', compact('foodItem', 'categories'));
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:food_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $foodItem->name = $request->name;
        $foodItem->category_id = $request->category_id;
        $foodItem->description = $request->description;
        $foodItem->price = $request->price;
        $foodItem->is_available = $request->is_available;

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $foodItem->image = $request->file('image')->store('food-items', 'public');
        }

        $foodItem->save();

        return redirect()->route('merchant.food-items.index')->with('success', 'Food item updated successfully.');
    }

    public function destroy(FoodItem $foodItem)
    {
        $this->authorize('delete', $foodItem);

        if ($foodItem->image) {
            Storage::disk('public')->delete($foodItem->image);
        }

        $foodItem->delete();

        return redirect()->route('merchant.food-items.index')->with('success', 'Food item deleted successfully.');
    }
}
