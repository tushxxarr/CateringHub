@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>

    @if(count($cartItems) > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Items in Cart</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </a>
                        <form action="{{ route('customer.cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="fas fa-trash-alt me-1"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.cart.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item['food_item']->image)
                                                <img src="{{ asset('storage/' . $item['food_item']->image) }}" alt="{{ $item['food_item']->name }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item['food_item']->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($item['food_item']->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['food_item']->price, 2) }}</td>
                                    <td style="width: 150px;">
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary qty-decrease" data-id="{{ $item['id'] }}">-</button>
                                            <input type="number" name="items[{{ $loop->index }}][quantity]" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" min="1" max="100">
                                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item['id'] }}">
                                            <button type="button" class="btn btn-outline-secondary qty-increase" data-id="{{ $item['id'] }}">+</button>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['subtotal'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="1">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
                                                <i class="fas fa-check me-1"></i> Checkout
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Start shopping now to add items to your cart!</p>
                <a href="{{ route('customer.merchants.index') }}" class="btn btn-primary">
                    <i class="fas fa-store me-1"></i> Browse Merchants
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity increase/decrease buttons
    document.querySelectorAll('.qty-decrease').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.nextElementSibling;
            const value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateSubtotal(input);
            }
        });
    });
    
    document.querySelectorAll('.qty-increase').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling.previousElementSibling;
            const value = parseInt(input.value);
            if (value < 100) {
                input.value = value + 1;
                updateSubtotal(input);
            }
        });
    });
    
    function updateSubtotal(input) {
        // This is just for visual feedback - actual calculation happens server-side
        const row = input.closest('tr');
        const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(',', ''));
        const quantity = parseInt(input.value);
        const subtotal = price * quantity;
        row.querySelector('td:nth-child(4)').textContent = subtotal.toFixed(2);
        
        // Update total
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach(input => {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(',', ''));
            const quantity = parseInt(input.value);
            total += price * quantity;
        });
        
        document.querySelector('tfoot tr.fw-bold td:nth-child(2)').textContent = total.toFixed(2);
    }
});
</script>
@endsection