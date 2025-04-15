@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Customer Dashboard</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $pendingOrdersCount }}</h1>
                <p class="card-text">Pending Orders</p>
            </div>
            <div class="card-footer bg-transparent border-primary">
                <a href="{{ route('customer.orders.index') }}#pending-orders" class="btn btn-sm btn-primary w-100">View Orders</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $completedOrdersCount }}</h1>
                <p class="card-text">Completed Orders</p>
            </div>
            <div class="card-footer bg-transparent border-success">
                <a href="{{ route('customer.orders.index') }}#completed-orders" class="btn btn-sm btn-success w-100">View History</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $unpaidInvoicesCount }}</h1>
                <p class="card-text">Unpaid Invoices</p>
            </div>
            <div class="card-footer bg-transparent border-warning">
                <a href="{{ route('customer.invoices.index') }}" class="btn btn-sm btn-warning w-100">View Invoices</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-info">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $cartItemsCount }}</h1>
                <p class="card-text">Items in Cart</p>
            </div>
            <div class="card-footer bg-transparent border-info">
                <a href="{{ route('customer.cart.index') }}" class="btn btn-sm btn-info w-100">View Cart</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Merchant</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->merchant->business_name ?? $order->merchant->company_name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No recent orders</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary btn-sm float-end">View All Orders</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Popular Merchants</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($popularMerchants as $merchant)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($merchant->logo)
                                <img src="{{ asset('storage/' . $merchant->logo) }}" alt="{{ $merchant->business_name ?? $merchant->company_name }}" class="me-2 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="me-2 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            {{ $merchant->business_name ?? $merchant->company_name }}
                        </div>
                        <a href="{{ route('customer.merchants.show', $merchant) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </li>
                    @empty
                    <li class="list-group-item text-center">No merchants available</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('customer.merchants.index') }}" class="btn btn-outline-success btn-sm float-end">Browse All Merchants</a>
            </div>
        </div>
    </div>
</div>
@endsection