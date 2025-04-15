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

        // Get all orders
        $orders = Order::where('customer_id', $customer->id)
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get orders by status
        $pendingOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $processingOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'processing')
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $completedOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cancelledOrders = Order::where('customer_id', $customer->id)
            ->where('status', 'cancelled')
            ->with('merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Count for badges
        $pendingCount = $pendingOrders->total();
        $processingCount = $processingOrders->total();

        return view('customer.orders.index', compact(
            'orders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'pendingCount',
            'processingCount'
        ));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('merchant', 'orderItems.foodItem', 'invoice');

        return view('customer.orders.show', compact('order'));
    }

    public function create()
    {
        // Get cart items
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
                    ];
                }
            }
        }

        // Pass the cart data to the view
        return view('customer.orders.create', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchant_profiles,id', // Updated here
            'food_items' => 'required|array',
            'food_items.*.id' => 'required|exists:food_items,id',
            'food_items.*.quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_time' => 'required',
            'delivery_address' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create the order
            $order = new Order();
            $order->customer_id = Auth::user()->customerProfile->id;
            $order->merchant_id = $request->merchant_id;
            $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            $order->delivery_date = $request->delivery_date;
            $order->delivery_time = $request->delivery_time;
            $order->delivery_address = $request->delivery_address;
            $order->notes = $request->notes;
            $order->status = 'pending';

            // Calculate totals
            $subtotal = 0;
            foreach ($request->food_items as $item) {
                $foodItem = FoodItem::findOrFail($item['id']);
                $subtotal += $foodItem->price * $item['quantity'];
            }

            $order->subtotal = $subtotal;
            $order->delivery_fee = 10000; // Fixed delivery fee
            $order->total_amount = $subtotal + $order->delivery_fee;
            $order->save();

            // Create order items
            foreach ($request->food_items as $item) {
                $foodItem = FoodItem::findOrFail($item['id']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->food_item_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $foodItem->price;
                $orderItem->subtotal = $foodItem->price * $item['quantity'];
                $orderItem->save();
            }

            // Create invoice automatically
            $invoice = new Invoice();
            $invoice->order_id = $order->id;
            $invoice->merchant_id = $order->merchant_id;
            $invoice->invoice_number = 'INV-' . strtoupper(Str::random(8));
            $invoice->amount = $order->total_amount;
            $invoice->status = 'pending';
            $invoice->due_date = now()->addDays(3); // Set due date to 3 days from now
            $invoice->save();

            // Clear the cart after successful order
            Session::forget('cart');

            DB::commit();

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! Your order number is #' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to place order. Error: ' . $e->getMessage());
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
