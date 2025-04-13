<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        if (!empty($cart)) {
            $foodItemIds = array_keys($cart);
            $foodItems = FoodItem::whereIn('id', $foodItemIds)->get()->keyBy('id');

            foreach ($cart as $id => $item) {
                if (isset($foodItems[$id])) {
                    $foodItem = $foodItems[$id];
                    $subtotal = $foodItem->price * $item['quantity'];
                    $total += $subtotal;

                    $cartItems[] = [
                        'id' => $id,
                        'food_item' => $foodItem,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal,
                        'merchant_id' => $foodItem->merchant_id,
                        'delivery_date' => $item['delivery_date'] ?? null,
                    ];
                }
            }
        }

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $foodItemId = $request->food_item_id;
        $quantity = $request->quantity;
        $deliveryDate = $request->delivery_date;

        $foodItem = FoodItem::findOrFail($foodItemId);

        $cart = Session::get('cart', []);

        // Check if we're mixing merchants in the cart
        if (!empty($cart)) {
            $firstItemId = array_key_first($cart);
            $firstItem = FoodItem::find($firstItemId);

            if ($firstItem && $firstItem->merchant_id != $foodItem->merchant_id) {
                return redirect()->back()->with('error', 'You can only order from one caterer at a time. Please clear your cart first.');
            }
        }

        if (isset($cart[$foodItemId])) {
            $cart[$foodItemId]['quantity'] += $quantity;
            $cart[$foodItemId]['delivery_date'] = $deliveryDate;
        } else {
            $cart[$foodItemId] = [
                'quantity' => $quantity,
                'delivery_date' => $deliveryDate,
            ];
        }

        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:food_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $cart = Session::get('cart', []);
        $deliveryDate = $request->delivery_date;

        foreach ($request->items as $item) {
            if (isset($cart[$item['id']])) {
                $cart[$item['id']]['quantity'] = $item['quantity'];
                $cart[$item['id']]['delivery_date'] = $deliveryDate;
            }
        }

        Session::put('cart', $cart);

        return redirect()->route('customer.cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('customer.cart.index')->with('success', 'Cart cleared successfully.');
    }
}
