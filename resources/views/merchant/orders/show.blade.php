@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Order Details</h2>
        <p class="text-muted">Order #{{ $order->id }}</p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <a href="{{ route('merchant.orders.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
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
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                        <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> 
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">Processing</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </p>
                        <p><strong>Delivery Date:</strong> {{ $order->delivery_date->format('M d, Y') }}</p>
                        <p><strong>Delivery Time:</strong> {{ $order->delivery_time }}</p>
                        <p><strong>Payment Status:</strong>
                            @if($order->is_paid)
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Delivery Address:</h6>
                    <p>{{ $order->delivery_address }}</p>
                </div>

                <div class="mb-3">
                    <h6>Notes:</h6>
                    <p>{{ $order->notes ?? 'No notes provided' }}</p>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->foodItem->image)
                                            <img src="{{ asset('storage/' . $item->foodItem->image) }}" alt="{{ $item->foodItem->name }}" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light me-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->foodItem->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($item->foodItem->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->tax > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax ({{ $order->tax_percentage }}%):</strong></td>
                                <td class="text-end">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($order->delivery_fee > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Delivery Fee:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Update Order Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('merchant.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status_note" class="form-label">Status Note (Optional)</label>
                        <textarea id="status_note" name="status_note" class="form-control" rows="3">{{ old('status_note') }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                <p><strong>Address:</strong> {{ $order->customer->address }}</p>
                <hr>
                <p><strong>Customer Since:</strong> {{ $order->customer->created_at->format('M d, Y') }}</p>
                <p><strong>Total Orders:</strong> {{ $customerOrderCount ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection