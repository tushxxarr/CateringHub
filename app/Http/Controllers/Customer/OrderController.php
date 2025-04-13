<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customerProfile;
        $orders = Order::where('customer_id', $customer->id)
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('merchant', 'orderItems.foodItem', 'invoice');

        return view('customer.orders.show', compact('order'));
    }

    public function create()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        $customer = Auth::user()->customerProfile;
        $cartItems = [];
        $total = 0;
        $merchantId = null;
        $deliveryDate = null;

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
                ];

                // Set merchant ID from the first item
                if (!$merchantId) {
                    $merchantId = $foodItem->merchant_id;
                }

                // Set delivery date from the first item
                if (!$deliveryDate) {
                    $deliveryDate = $item['delivery_date'];
                }
            }
        }

        return view('customer.orders.create', compact('cartItems', 'total', 'customer', 'merchantId', 'deliveryDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchant_profiles,id',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_time' => 'required',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        $customer = Auth::user()->customerProfile;
        $total = 0;

        $foodItemIds = array_keys($cart);
        $foodItems = FoodItem::whereIn('id', $foodItemIds)->get()->keyBy('id');

        foreach ($cart as $id => $item) {
            if (isset($foodItems[$id])) {
                $foodItem = $foodItems[$id];
                $subtotal = $foodItem->price * $item['quantity'];
                $total += $subtotal;
            }
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = new Order();
            $order->customer_id = $customer->id;
            $order->merchant_id = $request->merchant_id;
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->delivery_date = $request->delivery_date;
            $order->delivery_time = $request->delivery_time;
            $order->delivery_address = $request->delivery_address;
            $order->notes = $request->notes;
            $order->save();

            // Create order items
            foreach ($cart as $id => $item) {
                if (isset($foodItems[$id])) {
                    $foodItem = $foodItems[$id];

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->food_item_id = $foodItem->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->price = $foodItem->price;
                    $orderItem->save();
                }
            }

            // Create invoice
            $invoice = new Invoice();
            $invoice->order_id = $order->id;
            $invoice->invoice_number = 'INV-' . strtoupper(Str::random(10));
            $invoice->amount = $total;
            $invoice->status = 'pending';
            $invoice->due_date = now()->addDays(7);
            $invoice->save();

            DB::commit();

            // Clear cart
            Session::forget('cart');

            return redirect()->route('customer.orders.show', $order)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->status = 'cancelled';
        $order->save();

        if ($order->invoice) {
            $order->invoice->status = 'cancelled';
            $order->invoice->save();
        }

        return redirect()->route('customer.orders.show', $order)->with('success', 'Order cancelled successfully.');
    }
}
