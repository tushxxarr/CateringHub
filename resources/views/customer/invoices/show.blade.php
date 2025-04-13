@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice #{{ $invoice->invoice_number }}</li>
                </ol>
            </nav>
            <h2>Invoice #{{ $invoice->invoice_number }}</h2>
        </div>
        <div class="col-md-4 text-end">
            @if($invoice->status == 'pending')
                <form action="{{ route('customer.invoices.mark-as-paid', $invoice->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-credit-card me-1"></i> Pay Now
                    </button>
                </form>
            @endif
            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Invoices
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="mb-3">Invoice From:</h4>
                            <div class="mb-2">
                                <strong>{{ $invoice->order->merchant->company_name }}</strong>
                            </div>
                            <div>{{ $invoice->order->merchant->address }}</div>
                            <div>Phone: {{ $invoice->order->merchant->phone }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h4 class="mb-3">Invoice To:</h4>
                            <div class="mb-2">
                                <strong>{{ auth()->user()->name }}</strong>
                            </div>
                            <div>{{ auth()->user()->customerProfile->address }}</div>
                            <div>Phone: {{ auth()->user()->customerProfile->phone }}</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div><strong>Invoice Number:</strong> #{{ $invoice->invoice_number }}</div>
                            <div><strong>Order Number:</strong> #{{ $invoice->order->order_number }}</div>
                            <div><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($invoice->order->created_at)->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</div>
                            <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</div>
                            <div>
                                <strong>Status:</strong>
                                @if($invoice->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($invoice->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div><strong>{{ $item->foodItem->name }}</strong></div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->foodItem->description, 80) }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax (0%):</strong></td>
                                    <td class="text-end">Rp 0</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Delivery Information</h5>
                                </div>
                                <div class="card-body">
                                    <div><strong>Delivery Address:</strong> {{ $invoice->order->delivery_address }}</div>
                                    <div><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($invoice->order->delivery_date)->format('d M Y') }}</div>
                                    <div><strong>Delivery Time:</strong> {{ \Carbon\Carbon::parse($invoice->order->delivery_time)->format('H:i') }}</div>
                                    @if($invoice->order->notes)
                                        <div class="mt-2"><strong>Notes:</strong> {{ $invoice->order->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Payment Information</h5>
                                </div>
                                <div class="card-body">
                                    <p>Please make payment to:</p>
                                    <div><strong>Bank:</strong> Bank Central Asia (BCA)</div>
                                    <div><strong>Account Number:</strong> 1234567890</div>
                                    <div><strong>Account Name:</strong> CateringHub, Inc.</div>
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <i class="fas fa-info-circle me-1"></i> Please include your invoice number <strong>#{{ $invoice->invoice_number }}</strong> in the payment description.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="text-center">
                                <p class="mb-0">Thank you for your business!</p>
                                <p class="mb-0 text-muted">For any inquiries, please contact us at support@cateringhub.com or call +1 (123) 456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-outline-primary me-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print Invoice
                    </button>
                    
                    @if($invoice->status == 'pending')
                        <form action="{{ route('customer.invoices.mark-as-paid', $invoice->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-credit-card me-1"></i> Pay Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .navbar, .breadcrumb, .card-footer, .btn {
            display: none !important;
        }
        body {
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
@endpush
@endsection