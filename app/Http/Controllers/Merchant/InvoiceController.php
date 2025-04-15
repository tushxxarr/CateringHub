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

        // Convert string dates to Carbon instances if they're not already
        if (!is_object($invoice->issue_date)) {
            $invoice->issue_date = \Carbon\Carbon::parse($invoice->issue_date);
        }

        if (!is_object($invoice->due_date)) {
            $invoice->due_date = \Carbon\Carbon::parse($invoice->due_date);
        }

        if ($invoice->payment_date && !is_object($invoice->payment_date)) {
            $invoice->payment_date = \Carbon\Carbon::parse($invoice->payment_date);
        }

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
