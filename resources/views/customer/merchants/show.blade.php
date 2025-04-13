@extends('layouts.app')

@section('title', $merchant->company_name)

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        @if($merchant->logo)
            <img src="{{ asset('storage/' . $merchant->logo) }}" class="img-fluid rounded mb-3" alt="{{ $merchant->company_name }}">
        @else
            <img src="{{ asset('images/default-merchant.jpg') }}" class="img-fluid rounded mb-3" alt="{{ $merchant->company_name }}">
        @endif
    </div>
    <div class="col-md-8">
        <h2>{{ $merchant->company_name }}</h2>
        <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $merchant->address }}</p>
        <p class="text-muted"><i class="fas fa-phone"></i> {{ $merchant->phone }}</p>
        <div class="mb-3">
            <h5>About</h5>
            <p>{{ $merchant->description }}</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Food Menu</h5>
                    
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter by Category
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{ route('customer.merchants.show', $merchant->id) }}">All Categories</a></li>
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="{{ route('customer.merchants.show', ['merchant' => $merchant->id, 'category' => $category->id]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($categories as $category)
    @php
        $categoryItems = $foodItems->where('category_id', $category->id);
    @endphp

    @if($categoryItems->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4>{{ $category->name }}</h4>
                <hr>
            </div>
        </div>

        <div class="row mb-4">
            @foreach($categoryItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-food.jpg') }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                <span class="badge bg-primary">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            @if($item->is_available)
                                <form action="{{ route('customer.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="food_item_id" value="{{ $item->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary qty-decrease">-</button>
                                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="100">
                                            <button type="button" class="btn btn-outline-secondary qty-increase">+</button>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-danger mb-0"><i class="fas fa-times-circle me-1"></i> Currently Unavailable</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endforeach

@if($uncategorizedItems->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <h4>Other Items</h4>
            <hr>
        </div>
    </div>

    <div class="row mb-4">
        @foreach($uncategorizedItems as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-food.jpg') }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">{{ $item->name }}</h5>
                            <span class="badge bg-primary">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        @if($item->is_available)
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="food_item_id" value="{{ $item->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="input-group input-group-sm" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary qty-decrease">-</button>
                                        <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="100">
                                        <button type="button" class="btn btn-outline-secondary qty-increase">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </div>
                            </form>
                        @else
                            <p class="text-danger mb-0"><i class="fas fa-times-circle me-1"></i> Currently Unavailable</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@if($foodItems->count() == 0)
    <div class="row">
        <div class="col-12 text-center my-5">
            <div class="alert alert-info">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h4>No food items available</h4>
                <p>This merchant hasn't added any menu items yet. Please check back later.</p>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity increase/decrease buttons
        document.querySelectorAll('.qty-decrease').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });

        document.querySelectorAll('.qty-increase').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let input = this.previousElementSibling;
                let value = parseInt(input.value);
                if (value < 100) {
                    input.value = value + 1;
                }
            });
        });
    });
</script>
@endsection