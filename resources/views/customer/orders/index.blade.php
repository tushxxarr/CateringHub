@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>My Orders</h2>
        <p class="text-muted">Manage all your catering orders</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> New Order
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <ul class="nav nav-tabs card-header-tabs" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-orders" type="button" role="tab">
                    All Orders
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-orders" type="button" role="tab">
                    Pending
                    @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing-orders" type="button" role="tab">
                    Processing
                    @if(isset($processingCount) && $processingCount > 0)
                    <span class="badge bg-info text-dark ms-1">{{ $processingCount }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-orders" type="button" role="tab">
                    Completed
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled-orders" type="button" role="tab">
                    Cancelled
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="orderTabContent">
            <div class="tab-pane fade show active" id="all-orders" role="tabpanel">
                @include('customer.orders._order_list', ['orders' => $orders])
            </div>
            <div class="tab-pane fade" id="pending-orders" role="tabpanel">
                @include('customer.orders._order_list', ['orders' => $pendingOrders ?? []])
            </div>
            <div class="tab-pane fade" id="processing-orders" role="tabpanel">
                @include('customer.orders._order_list', ['orders' => $processingOrders ?? []])
            </div>
            <div class="tab-pane fade" id="completed-orders" role="tabpanel">
                @include('customer.orders._order_list', ['orders' => $completedOrders ?? []])
            </div>
            <div class="tab-pane fade" id="cancelled-orders" role="tabpanel">
                @include('customer.orders._order_list', ['orders' => $cancelledOrders ?? []])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Activate tab based on URL hash if present
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`[data-bs-target="${hash}"]`);
            if (tab) {
                const tabTrigger = new bootstrap.Tab(tab);
                tabTrigger.show();
            }
        }
    });
</script>
@endsection