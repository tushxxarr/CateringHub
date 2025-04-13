<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchantProfile;
        $invoices = Invoice::whereHas('order', function ($query) use ($merchant) {
            $query->where('merchant_id', $merchant->id);
        })
            ->with('order.customer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('merchant.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('order.customer', 'order.merchant', 'order.orderItems.foodItem');

        return view('merchant.invoices.show', compact('invoice'));
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $invoice->status = $request->status;
        $invoice->save();

        return redirect()->route('merchant.invoices.show', $invoice)->with('success', 'Invoice status updated successfully.');
    }
}
