@extends('layouts.app')

@section('title', 'Invoice Detail')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Invoice #{{ $invoice->id }}</h2>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <a href="{{ route('merchant.invoices.index') }}" class="btn btn-outline-secondary">Back to Invoices</a>
            <button onclick="window.print()" class="btn btn-outline-primary">Print Invoice</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3">Merchant</h5>
                <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                <p class="mb-1">{{ auth()->user()->merchant->business_address ?? 'N/A' }}</p>
                <p class="mb-1">Phone: {{ auth()->user()->phone ?? 'N/A' }}</p>
                <p class="mb-1">Email: {{ auth()->user()->email }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="mb-3">Customer</h5>
                <p class="mb-1"><strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong></p>
                <p class="mb-1">{{ $invoice->order->customer->address ?? 'N/A' }}</p>
                <p class="mb-1">Phone: {{ $invoice->order->customer->phone ?? 'N/A' }}</p>
                <p class="mb-1">Email: {{ $invoice->order->customer->email ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3">Invoice Details</h5>
                <p class="mb-1"><strong>Invoice Number:</strong> #{{ $invoice->id }}</p>
                <p class="mb-1"><strong>Order Number:</strong> #{{ $invoice->order_id }}</p>
                <p class="mb-1"><strong>Issue Date:</strong> {{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : 'N/A' }}</p>
                <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="mb-3">Payment Status</h5>
                @if($invoice->status == 'pending')
                    <span class="badge bg-warning p-2 fs-6">Pending</span>
                @elseif($invoice->status == 'paid')
                    <span class="badge bg-success p-2 fs-6">Paid</span>
                    @if($invoice->payment_date)
                        <p class="mt-2"><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($invoice->payment_date)->format('d M Y') }}</p>
                    @endif
                @elseif($invoice->status == 'overdue')
                    <span class="badge bg-danger p-2 fs-6">Overdue</span>
                @endif
            </div>
        </div>

        <h5 class="mb-3">Order Items</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->order->orderItems as $item)
                    <tr>
                        <td>{{ $item->food_item->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotal</th>
                        <td>Rp {{ number_format($invoice->order->subtotal ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Tax ({{ $invoice->order->tax_percentage ?? 0 }}%)</th>
                        <td>Rp {{ number_format($invoice->order->tax_amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        @if($invoice->notes)
        <div class="row mt-4">
            <div class="col-md-12">
                <h5 class="mb-3">Notes</h5>
                <div class="p-3 bg-light rounded">
                    {{ $invoice->notes }}
                </div>
            </div>
        </div>
        @endif
        
        @if($invoice->status == 'pending')
        <div class="row mt-4">
            <div class="col-md-12">
                <form action="{{ route('merchant.invoices.update-status', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Update Invoice Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection