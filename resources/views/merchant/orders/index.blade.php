@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Orders</h2>
</div>

<div class="card">
    <div class="card-header bg-white">
        <form action="{{ route('merchant.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by ID or customer..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('merchant.orders.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
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
                            <div class="btn-group" role="group">
                                <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-tasks"></i> Status
                                </button>
                                <ul class="dropdown-menu">
                                    @if($order->status != 'processing')
                                    <li>
                                        <form action="{{ route('merchant.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="dropdown-item">Mark as Processing</button>
                                        </form>
                                    </li>
                                    @endif
                                    @if($order->status != 'completed')
                                    <li>
                                        <form action="{{ route('merchant.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="dropdown-item">Mark as Completed</button>
                                        </form>
                                    </li>
                                    @endif
                                    @if($order->status != 'cancelled' && $order->status != 'completed')
                                    <li>
                                        <form action="{{ route('merchant.orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="dropdown-item">Cancel Order</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $orders->links() ?? '' }}
    </div>
</div>
@endsection