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
                <form action="{{ route('customer.orders.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="merchant_id" class="form-label">Select Merchant</label>
                        <select id="merchant_id" name="merchant_id" class="form-select @error('merchant_id') is-invalid @enderror" required>
                            <option value="">-- Select Merchant --</option>
                            @foreach($merchants ?? [] as $merchant)
                                <option value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>
                                    {{ $merchant->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('merchant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="merchant-items-container" class="mb-4" style="{{ old('merchant_id') ? '' : 'display: none;' }}">
                        <h5 class="mb-3">Select Food Items</h5>
                        <div id="food-items-list" class="list-group mb-3">
                            <!-- Food items will be loaded here via AJAX -->
                            @if(old('food_items'))
                                <!-- Show previously selected items if validation failed -->
                                @foreach(old('food_items', []) as $index => $item)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                {{ $item['name'] ?? 'Food Item' }}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-outline-secondary decrease-qty">-</button>
                                                    <input type="number" name="food_items[{{ $index }}][quantity]" class="form-control text-center item-quantity" value="{{ $item['quantity'] ?? 1 }}" min="1" readonly data-price="{{ $item['price'] ?? 0 }}">
                                                    <button type="button" class="btn btn-outline-secondary increase-qty">+</button>
                                                </div>
                                                <input type="hidden" name="food_items[{{ $index }}][id]" value="{{ $item['id'] ?? '' }}">
                                                <input type="hidden" name="food_items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                                                <input type="hidden" name="food_items[{{ $index }}][price]" value="{{ $item['price'] ?? 0 }}">
                                            </div>
                                            <div class="col-md-2 text-end">
                                                Rp <span class="item-subtotal">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-sm btn-danger remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="no-items-message" class="text-center p-4 border rounded" style="{{ old('food_items') ? 'display: none;' : '' }}">
                            <i class="fas fa-utensils fa-2x mb-3 text-muted"></i>
                            <p>No food items selected. Please select items from the menu.</p>
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
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submit-order" disabled>Place Order</button>
                    </div>
                </form>
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
                    <div class="text-center p-4" id="empty-cart-message">
                        <i class="fas fa-shopping-cart fa-2x mb-3 text-muted"></i>
                        <p>Your cart is empty</p>
                        <small class="text-muted">Select items to order</small>
                    </div>
                    <div id="order-items-list" style="display: none;">
                        <div class="list-group list-group-flush mb-3" id="summary-items-list">
                            <!-- Items will be populated here via JavaScript -->
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp <span id="subtotal-amount">0</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>Rp <span id="delivery-fee">10.000</span></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>Rp <span id="total-amount">10.000</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Food Items Modal -->
<div class="modal fade" id="foodItemsModal" tabindex="-1" aria-labelledby="foodItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="foodItemsModalLabel">Select Food Items</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-cols-1 row-cols-md-2 g-4" id="modal-food-items-container">
                    <!-- Food items will be loaded here -->
                </div>
                <div class="text-center p-5" id="modal-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading food items...</p>
                </div>
                <div class="text-center p-5" id="modal-no-items" style="display: none;">
                    <i class="fas fa-utensils fa-3x mb-3 text-muted"></i>
                    <h5>No Food Items Available</h5>
                    <p class="text-muted">This merchant has no food items available for order</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="select-items">Add Selected Items</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const merchantSelect = document.getElementById('merchant_id');
        const merchantItemsContainer = document.getElementById('merchant-items-container');
        const foodItemsList = document.getElementById('food-items-list');
        const noItemsMessage = document.getElementById('no-items-message');
        const submitOrderBtn = document.getElementById('submit-order');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const orderItemsList = document.getElementById('order-items-list');
        const summaryItemsList = document.getElementById('summary-items-list');
        const subtotalAmount = document.getElementById('subtotal-amount');
        const totalAmount = document.getElementById('total-amount');
        
        // When merchant is selected
        merchantSelect.addEventListener('change', function() {
            if (this.value) {
                merchantItemsContainer.style.display = 'block';
                // Here you would typically load food items via AJAX
                // For the mockup, let's simulate a modal to select items
                const foodItemsModal = new bootstrap.Modal(document.getElementById('foodItemsModal'));
                foodItemsModal.show();
            } else {
                merchantItemsContainer.style.display = 'none';
                clearOrderItems();
            }
        });
        
        // Update order summary whenever items change
        function updateOrderSummary() {
            const items = document.querySelectorAll('.list-group-item .item-quantity');
            let subtotal = 0;
            const deliveryFee = 10000; // Fixed delivery fee
            
            // Clear summary list
            summaryItemsList.innerHTML = '';
            
            if (items.length > 0) {
                items.forEach(item => {
                    const container = item.closest('.list-group-item');
                    const itemName = container.querySelector('input[name$="[name]"]').value;
                    const itemPrice = parseFloat(item.dataset.price);
                    const itemQty = parseInt(item.value);
                    const itemTotal = itemPrice * itemQty;
                    subtotal += itemTotal;
                    
                    // Add to summary
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
                });
                
                emptyCartMessage.style.display = 'none';
                orderItemsList.style.display = 'block';
                submitOrderBtn.disabled = false;
            } else {
                emptyCartMessage.style.display = 'block';
                orderItemsList.style.display = 'none';
                submitOrderBtn.disabled = true;
            }
            
            subtotalAmount.textContent = numberFormat(subtotal);
            totalAmount.textContent = numberFormat(subtotal + deliveryFee);
        }
        
        // Helper function to format numbers
        function numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Clear all order items
        function clearOrderItems() {
            foodItemsList.innerHTML = '';
            noItemsMessage.style.display = 'block';
            updateOrderSummary();
        }
        
        // Event delegation for quantity changes and item removal
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
                const item = e.target.closest('.list-group-item');
                item.remove();
                if (foodItemsList.children.length === 0) {
                    noItemsMessage.style.display = 'block';
                }
                updateOrderSummary();
            }
        });
        
        // Update item subtotal when quantity changes
        function updateItemSubtotal(quantityInput) {
            const price = parseFloat(quantityInput.dataset.price);
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;
            const container = quantityInput.closest('.list-group-item');
            container.querySelector('.item-subtotal').textContent = numberFormat(subtotal);
            updateOrderSummary();
        }
        
        // Initialize order summary on page load
        updateOrderSummary();
    });
</script>
@endsection