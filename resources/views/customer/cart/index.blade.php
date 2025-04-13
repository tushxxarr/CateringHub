@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Shopping Cart</h2>
        <p class="text-muted">Review your items before checkout</p>
    </div>
</div>

@if(count($cartItems) > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                        <form action="{{ route('customer.cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('images/default-food.jpg') }}" alt="{{ $item['name'] }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                <small class="text-muted">{{ $item['merchant_name'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.update') }}" method="POST" class="quantity-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <button type="button" class="btn btn-outline-secondary qty-decrease">-</button>
                                                <input type="number" name="quantity" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" min="1" max="100">
                                                <button type="button" class="btn btn-outline-secondary qty-increase">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee:</span>
                        <span>Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax ({{ $taxRate }}%):</span>
                        <span>Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-success">
                            <i class="fas fa-shopping-cart me-2"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            @if(!empty($recommendedItems))
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">You Might Also Like</h5>
                </div>
                <div class="card-body p-2">
                    @foreach($recommendedItems as $item)
                    <div class="d-flex align-items-center p-2 border-bottom">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-food.jpg') }}" alt="{{ $item->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $item->name }}</h6>
                            <small class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                        </div>
                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="food_item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12 text-center my-5">
            <div class="alert alert-info">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Your cart is empty</h4>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('customer.merchants.index') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity buttons functionality
        document.querySelectorAll('.qty-decrease').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    submitForm(this.closest('form'));
                }
            });
        });

        document.querySelectorAll('.qty-increase').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                if (value < 100) {
                    input.value = value + 1;
                    submitForm(this.closest('form'));
                }
            });
        });

        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.addEventListener('change', function() {
                submitForm(this.closest('form'));
            });
        });

        function submitForm(form) {
            form.submit();
        }
    });
</script>
@endsection