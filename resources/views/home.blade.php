@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Welcome to CateringHub!</h1>
    <p class="lead">Find the best catering services for your events, parties, and gatherings.</p>
    <hr class="my-4">
    <p>Browse our wide selection of caterers and food items to make your event special.</p>
    <a class="btn btn-primary btn-lg" href="{{ route('customer.merchants.index') }}" role="button">Browse Merchants</a>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h2 class="text-center mb-4">How It Works</h2>
    </div>
</div>

<div class="row text-center">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Find Caterers</h5>
                <p class="card-text">Browse through our verified catering merchants and explore their menus.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <i class="fas fa-utensils fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Order Food</h5>
                <p class="card-text">Add food items to your cart and place your order with ease.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <i class="fas fa-truck fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Get Delivered</h5>
                <p class="card-text">Sit back and relax as your catering order is prepared and delivered.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h2 class="text-center mb-4">Featured Merchants</h2>
    </div>
</div>

<div class="row">
    @foreach($featuredMerchants as $merchant)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <!-- Display merchant logo with fallback -->
            <img src="{{ $merchant->logo ? asset('storage/' . $merchant->logo) : asset('images/default-merchant.jpg') }}"
                class="card-img-top"
                alt="{{ $merchant->company_name }}"
                style="object-fit: cover; height: 200px;">

            <div class="card-body">
                <!-- Display company name -->
                <h5 class="card-title">{{ $merchant->company_name }}</h5>

                <!-- Display merchant description -->
                <p class="card-text">{{ $merchant->description }}</p>

                <div class="row mb-4">
                    <div class="col-6">
                        <!-- Display address with location icon -->
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <small>{{ $merchant->address }}</small>
                    </div>
                    <div class="col-6">
                        <!-- Display phone number with phone icon -->
                        <i class="fas fa-phone text-success me-1"></i>
                        <small>{{ $merchant->phone }}</small>
                    </div>
                </div>

                <!-- Link to view menu page -->
                <a href="{{ route('customer.merchants.show', $merchant) }}" class="btn btn-primary w-100">
                    View Menu
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection