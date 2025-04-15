<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customerProfile;

        // Get all invoices
        $invoices = Invoice::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->with('order.merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get pending invoices
        $pendingInvoices = Invoice::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'pending')
            ->with('order.merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get paid invoices
        $paidInvoices = Invoice::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'paid')
            ->with('order.merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get cancelled invoices
        $cancelledInvoices = Invoice::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->where('status', 'cancelled')
            ->with('order.merchant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.invoices.index', compact(
            'invoices',
            'pendingInvoices',
            'paidInvoices',
            'cancelledInvoices'
        ));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('order.customer', 'order.merchant', 'order.orderItems.foodItem');

        return view('customer.invoices.show', compact('invoice'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        $this->authorize('markAsPaid', $invoice);

        if ($invoice->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending invoices can be marked as paid.');
        }

        $invoice->status = 'paid';
        $invoice->save();

        return redirect()->route('customer.invoices.show', $invoice)->with('success', 'Invoice marked as paid successfully.');
    }
}
