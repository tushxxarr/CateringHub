@extends('layouts.app')

@section('title', 'My Invoices')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>My Invoices</h2>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInvoice" placeholder="Search invoices...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#all" data-bs-toggle="tab">All Invoices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#pending" data-bs-toggle="tab">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#paid" data-bs-toggle="tab">Paid</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#cancelled" data-bs-toggle="tab">Cancelled</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all">
                            @if($invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Order #</th>
                                                <th>Merchant</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $invoice->order) }}">
                                                        {{ $invoice->order->order_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->order->merchant->company_name }}</td>
                                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                                <td>
                                                    @if($invoice->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($invoice->status === 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($invoice->status === 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">View</a>
                                                    @if($invoice->status === 'pending')
                                                        <form action="{{ route('customer.invoices.mark-as-paid', $invoice) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success">Pay</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $invoices->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                                    <h4>No invoices found</h4>
                                    <p class="text-muted">You don't have any invoices yet.</p>
                                    <a href="{{ route('customer.merchants.index') }}" class="btn btn-primary mt-3">Browse Merchants</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="pending">
                            @if($pendingInvoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Order #</th>
                                                <th>Merchant</th>
                                                <th>Amount</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $invoice->order) }}">
                                                        {{ $invoice->order->order_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->order->merchant->company_name }}</td>
                                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">View</a>
                                                    <form action="{{ route('customer.invoices.mark-as-paid', $invoice) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success">Pay</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $pendingInvoices->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                    <h4>No pending invoices</h4>
                                    <p class="text-muted">You don't have any pending invoices at the moment.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="paid">
                            @if($paidInvoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Order #</th>
                                                <th>Merchant</th>
                                                <th>Amount</th>
                                                <th>Paid Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paidInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $invoice->order) }}">
                                                        {{ $invoice->order->order_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->order->merchant->company_name }}</td>
                                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->updated_at)->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $paidInvoices->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                                    <h4>No paid invoices</h4>
                                    <p class="text-muted">You don't have any paid invoices yet.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="cancelled">
                            @if($cancelledInvoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Order #</th>
                                                <th>Merchant</th>
                                                <th>Amount</th>
                                                <th>Cancelled Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cancelledInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>
                                                    <a href="{{ route('customer.orders.show', $invoice->order) }}">
                                                        {{ $invoice->order->order_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->order->merchant->company_name }}</td>
                                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->updated_at)->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('customer.invoices.show', $invoice) }}" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $cancelledInvoices->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-ban fa-4x text-muted mb-3"></i>
                                    <h4>No cancelled invoices</h4>
                                    <p class="text-muted">You don't have any cancelled invoices.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInvoice');
        const tableRows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection