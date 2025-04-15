@extends('layouts.app')

@section('title', 'Create New Order')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">My Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create New Order</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create New Order</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(empty($cartItems))
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> Your cart is empty. Please add items to your cart first.</p>
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-sm btn-primary">Browse Merchants</a>
                    </div>
                @else
                    <form action="{{ route('customer.orders.store') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden merchant ID from cart -->
                        <input type="hidden" name="merchant_id" value="{{ $cartItems[0]['merchant_id'] ?? '' }}">
                        
                        <div class="mb-4">
                            <h5 class="mb-3">Items in Your Cart</h5>
                            <div class="list-group mb-3">
                                @foreach($cartItems ?? [] as $index => $item)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                {{ $item['food_item']->name }}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-outline-secondary decrease-qty">-</button>
                                                    <input type="number" name="food_items[{{ $index }}][quantity]" class="form-control text-center item-quantity" 
                                                        value="{{ $item['quantity'] }}" min="1" readonly data-price="{{ $item['food_item']->price }}">
                                                    <button type="button" class="btn btn-outline-secondary increase-qty">+</button>
                                                </div>
                                                <input type="hidden" name="food_items[{{ $index }}][id]" value="{{ $item['food_item']->id }}">
                                                <input type="hidden" name="food_items[{{ $index }}][name]" value="{{ $item['food_item']->name }}">
                                                <input type="hidden" name="food_items[{{ $index }}][price]" value="{{ $item['food_item']->price }}">
                                            </div>
                                            <div class="col-md-2 text-end">
                                                Rp <span class="item-subtotal">{{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-sm btn-danger remove-item" data-id="{{ $item['id'] }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" 
                                value="{{ old('delivery_date', now()->addDays(1)->format('Y-m-d')) }}" min="{{ now()->addDays(1)->format('Y-m-d') }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_time" class="form-label">Delivery Time</label>
                            <input type="time" class="form-control @error('delivery_time') is-invalid @enderror" id="delivery_time" name="delivery_time" 
                                value="{{ old('delivery_time', '12:00') }}" required>
                            @error('delivery_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" 
                                rows="3" required>{{ old('delivery_address', auth()->user()->customerProfile->address ?? '') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes <span class="text-muted">(Optional)</span></label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" 
                                rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('customer.cart.index') }}" class="btn btn-secondary">Back to Cart</a>
                            <button type="submit" class="btn btn-primary" id="submit-order" {{ empty($cartItems) ? 'disabled' : '' }}>Place Order</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div id="order-items-summary">
                    @if(empty($cartItems))
                    <div class="text-center p-4" id="empty-cart-message">
                        <i class="fas fa-shopping-cart fa-2x mb-3 text-muted"></i>
                        <p>Your cart is empty</p>
                        <small class="text-muted">Add items to your cart first</small>
                    </div>
                    @else
                    <div id="order-items-list">
                        <div class="list-group list-group-flush mb-3" id="summary-items-list">
                            @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-medium">{{ $item['food_item']->name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $item['quantity'] }} x Rp {{ number_format($item['food_item']->price, 0, ',', '.') }}</small>
                                </div>
                                <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp <span id="subtotal-amount">{{ number_format($total, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>Rp <span id="delivery-fee">10.000</span></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>Rp <span id="total-amount">{{ number_format($total + 10000, 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const foodItemsList = document.querySelector('.list-group');
        const submitOrderBtn = document.getElementById('submit-order');
        
        // Event delegation for quantity changes and item removal
        if (foodItemsList) {
            foodItemsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('increase-qty')) {
                    const input = e.target.previousElementSibling;
                    input.value = parseInt(input.value) + 1;
                    updateItemSubtotal(input);
                } else if (e.target.classList.contains('decrease-qty')) {
                    const input = e.target.nextElementSibling;
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateItemSubtotal(input);
                    }
                } else if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                    const btn = e.target.closest('.remove-item');
                    const itemId = btn.dataset.id;
                    
                    // Send AJAX request to remove item from cart
                    window.location.href = "{{ route('customer.cart.remove', '') }}/" + itemId;
                }
            });
        }
        
        // Update item subtotal when quantity changes
        function updateItemSubtotal(quantityInput) {
            const price = parseFloat(quantityInput.dataset.price);
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;
            const container = quantityInput.closest('.list-group-item');
            container.querySelector('.item-subtotal').textContent = numberFormat(subtotal);
            updateOrderSummary();
        }
        
        // Helper function to format numbers
        function numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Update order summary
        function updateOrderSummary() {
            const items = document.querySelectorAll('.item-quantity');
            let subtotal = 0;
            const deliveryFee = 10000; // Fixed delivery fee
            
            // Clear summary list 
            const summaryItemsList = document.getElementById('summary-items-list');
            if (summaryItemsList) {
                summaryItemsList.innerHTML = '';
            }
            
            if (items.length > 0) {
                items.forEach(item => {
                    const container = item.closest('.list-group-item');
                    const itemName = container.querySelector('input[name$="[name]"]').value;
                    const itemPrice = parseFloat(item.dataset.price);
                    const itemQty = parseInt(item.value);
                    const itemTotal = itemPrice * itemQty;
                    subtotal += itemTotal;
                    
                    // Add to summary
                    if (summaryItemsList) {
                        const summaryItem = document.createElement('div');
                        summaryItem.className = 'd-flex justify-content-between align-items-center mb-2';
                        summaryItem.innerHTML = `
                            <div>
                                <span class="fw-medium">${itemName}</span>
                                <br>
                                <small class="text-muted">${itemQty} x Rp ${numberFormat(itemPrice)}</small>
                            </div>
                            <span>Rp ${numberFormat(itemTotal)}</span>
                        `;
                        summaryItemsList.appendChild(summaryItem);
                    }
                });
                
                if (submitOrderBtn) {
                    submitOrderBtn.disabled = false;
                }
            } else {
                if (submitOrderBtn) {
                    submitOrderBtn.disabled = true;
                }
                // Redirect to cart if all items removed
                if (items.length === 0) {
                    window.location.href = "{{ route('customer.cart.index') }}";
                }
            }
            
            const subtotalEl = document.getElementById('subtotal-amount');
            const totalEl = document.getElementById('total-amount');
            
            if (subtotalEl) {
                subtotalEl.textContent = numberFormat(subtotal);
            }
            if (totalEl) {
                totalEl.textContent = numberFormat(subtotal + deliveryFee);
            }
            
            // Update cart via AJAX for quantity changes
            const formData = new FormData();
            items.forEach((item, index) => {
                const container = item.closest('.list-group-item');
                const itemId = container.querySelector('input[name$="[id]"]').value;
                formData.append(`items[${index}][id]`, itemId);
                formData.append(`items[${index}][quantity]`, item.value);
            });
            formData.append('delivery_date', document.getElementById('delivery_date').value);
            
            fetch("{{ route('customer.cart.update') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }
    });
</script>
@endsection