@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>My Profile</h2>
        <p class="text-muted">View and manage your merchant information</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Merchant Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ $profile->logo ? asset('storage/' . $profile->logo) : asset('images/default-merchant.jpg') }}" 
                         alt="Merchant Logo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Owner Name</p>
                    <p>{{ auth()->user()->name }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Email</p>
                    <p>{{ auth()->user()->email }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Company Name</p>
                    <p>{{ $profile->company_name }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Phone</p>
                    <p>{{ $profile->phone }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Business Address</p>
                    <p>{{ $profile->address }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Business Description</p>
                    <p>{{ $profile->description ?? 'Not specified' }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Member Since</p>
                    <p>{{ $profile->created_at->format('M Y') }}</p>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('merchant.profile.edit') }}" class="btn btn-primary w-100">Edit Profile</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Business Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $foodItemsCount ?? 0 }}</h3>
                                <p class="text-muted">Food Items</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-success text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $totalOrders ?? 0 }}</h3>
                                <p class="text-muted">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-info text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                                <p class="text-muted">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                @if(!empty($recentOrders) && count($recentOrders) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->customer->user->name }}</td>
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
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">No recent orders</p>
                @endif
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('merchant.orders.index') }}" class="btn btn-outline-success btn-sm float-end">View All Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection