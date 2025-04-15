@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Order #{{ $order->order_number }}</h2>
        </div>
        <div class="col-md-4 text-end">
            @if($order->status === 'pending')
                <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                </form>
            @endif
            <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary ms-2">Back to Orders</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                            <p><strong>Date Placed:</strong> {{ $order->created_at->format('F d, Y H:i') }}</p>
                            <p><strong>Status:</strong> 
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Merchant:</strong> {{ $order->merchant->company_name }}</p>
                            <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') }}</p>
                            <p><strong>Delivery Time:</strong> {{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Delivery Address:</h6>
                        <p class="mb-0">{{ $order->delivery_address }}</p>
                    </div>

                    @if($order->notes)
                    <div class="mb-3">
                        <h6>Notes:</h6>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->foodItem && $item->foodItem->image)
                                                <img src="{{ asset('storage/' . $item->foodItem->image) }}" alt="{{ $item->foodItem->name }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->foodItem ? $item->foodItem->name : 'Unknown Item' }}</h6>
                                                <small class="text-muted">{{ $item->foodItem ? Str::limit($item->foodItem->description, 50) : '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Delivery Fee:</td>
                                    <td class="text-end">Rp {{ number_format($order->delivery_fee ?? 10000, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    @if($order->invoice)
                        <p><strong>Invoice Number:</strong> #{{ $order->invoice->invoice_number }}</p>
                        <p><strong>Amount:</strong> Rp {{ number_format($order->invoice->amount, 0, ',', '.') }}</p>
                        <p><strong>Status:</strong> 
                            @if($order->invoice->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($order->invoice->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->invoice->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </p>
                        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($order->invoice->due_date)->format('F d, Y') }}</p>
                        
                        @if($order->invoice->status === 'pending')
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('customer.invoices.show', $order->invoice) }}" class="btn btn-primary">View Invoice</a>
                                <form action="{{ route('customer.invoices.mark-as-paid', $order->invoice) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100">Pay Now</button>
                                </form>
                            </div>
                        @else
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('customer.invoices.show', $order->invoice) }}" class="btn btn-primary">View Invoice</a>
                            </div>
                        @endif
                    @else
                        <p class="text-center">No invoice has been generated for this order yet.</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Merchant Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($order->merchant->logo)
                            <img src="{{ asset('storage/' . $order->merchant->logo) }}" alt="{{ $order->merchant->company_name }}" class="img-fluid rounded" style="max-height: 100px;">
                        @else
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-store fa-3x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="text-center mb-3">{{ $order->merchant->company_name }}</h5>
                    
                    <p><strong><i class="fas fa-map-marker-alt me-2"></i> Address:</strong><br>
                    {{ $order->merchant->address }}</p>
                    
                    <p><strong><i class="fas fa-phone me-2"></i> Phone:</strong><br>
                    {{ $order->merchant->phone }}</p>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('customer.merchants.show', $order->merchant->id) }}" class="btn btn-outline-primary">Visit Merchant Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection