<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Merchant</th>
                <th>Total Amount</th>
                <th>Delivery Date</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($order->merchant->logo)
                        <img src="{{ asset('storage/' . $order->merchant->logo) }}" alt="{{ $order->merchant->company_name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-store text-white"></i>
                        </div>
                        @endif
                        <div>
                            <p class="fw-bold mb-0">{{ $order->merchant->company_name }}</p>
                            <small class="text-muted">{{ Str::limit($order->merchant->description, 50) }}</small>
                        </div>
                    </div>
                </td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                    <div>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}</small>
                </td>
                <td>
                    @if($order->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($order->status == 'processing')
                        <span class="badge bg-info text-dark">Processing</span>
                    @elseif($order->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>
                <td>
                    @if($order->invoice && $order->invoice->status == 'pending')
                        <span class="badge bg-danger">Unpaid</span>
                    @elseif($order->invoice && $order->invoice->status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @elseif($order->invoice && $order->invoice->status == 'cancelled')
                        <span class="badge bg-secondary">Cancelled</span>
                    @else
                        <span class="badge bg-secondary">No Invoice</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($order->status == 'pending')
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                        @if($order->invoice && $order->invoice->status == 'pending')
                        <a href="{{ route('customer.invoices.show', $order->invoice) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-credit-card"></i> Pay
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5>No orders found</h5>
                        <p class="text-muted">You haven't placed any orders yet</p>
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-primary mt-2">
                            Browse Merchants
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($orders) && $orders->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endif