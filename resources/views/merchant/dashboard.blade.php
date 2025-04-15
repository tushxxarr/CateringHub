@extends('layouts.app')

@section('title', 'Merchant Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">Merchant Dashboard</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $foodItemsCount ?? 0 }}</h1>
                <p class="card-text">Food Items</p>
            </div>
            <div class="card-footer bg-transparent border-primary">
                <a href="{{ route('merchant.food-items.index') }}" class="btn btn-sm btn-primary w-100">Manage Food Items</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $pendingOrdersCount ?? 0 }}</h1>
                <p class="card-text">Pending Orders</p>
            </div>
            <div class="card-footer bg-transparent border-success">
                <a href="{{ route('merchant.orders.index') }}" class="btn btn-sm btn-success w-100">View Orders</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h1 class="display-4">{{ $unpaidInvoicesCount ?? 0 }}</h1>
                <p class="card-text">Unpaid Invoices</p>
            </div>
            <div class="card-footer bg-transparent border-warning">
                <a href="{{ route('merchant.invoices.index') }}" class="btn btn-sm btn-warning w-100">View Invoices</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-info">
            <div class="card-body text-center">
                <h1 class="display-5">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h1>
                <p class="card-text mt-3">Total Revenue</p>
            </div>
            <div class="card-footer bg-transparent border-info">
                <a href="{{ route('merchant.invoices.index') }}" class="btn btn-sm btn-info w-100">View Details</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->customer->user->name ?? '-' }}</td>
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
                                        <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
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
                <a href="{{ route('merchant.orders.index') }}" class="btn btn-outline-primary btn-sm float-end">View All Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
