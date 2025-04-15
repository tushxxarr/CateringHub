@extends('layouts.app')

@section('title', 'Merchant Invoices')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Invoices</h2>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('merchant.invoices.index') }}">All Invoices</a></li>
                <li><a class="dropdown-item" href="{{ route('merchant.invoices.index', ['status' => 'pending']) }}">Pending</a></li>
                <li><a class="dropdown-item" href="{{ route('merchant.invoices.index', ['status' => 'paid']) }}">Paid</a></li>
                <li><a class="dropdown-item" href="{{ route('merchant.invoices.index', ['status' => 'overdue']) }}">Overdue</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices ?? [] as $invoice)
                    <tr>
                        <td>#{{ $invoice->invoice_number ?? $invoice->id }}</td>
                        <td>{{ $invoice->order->customer->name ?? 'N/A' }}</td>
                        <td>#{{ $invoice->order_id }}</td>
                        <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                        <td>{{ isset($invoice->created_at) ? $invoice->created_at->format('d M Y') : 'N/A' }}</td>
                        <td>{{ isset($invoice->due_date) && $invoice->due_date ? date('d M Y', strtotime($invoice->due_date)) : 'N/A' }}</td>
                        <td>
                            @if($invoice->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($invoice->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($invoice->status == 'overdue')
                                <span class="badge bg-danger">Overdue</span>
                            @elseif($invoice->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('merchant.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No invoices found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($invoices) && method_exists($invoices, 'links'))
    <div class="card-footer">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection