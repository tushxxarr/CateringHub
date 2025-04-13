@extends('layouts.app')

@section('title', 'Browse Merchants')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>Browse Catering Merchants</h2>
        <p class="text-muted">Discover the best catering services for your needs</p>
    </div>
    <div class="col-md-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search merchants..." id="merchant-search">
            <button class="btn btn-primary" type="button" id="search-button">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form id="filter-form" action="{{ route('customer.merchants.index') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Categories</label>
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" 
                                    {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="row" id="merchants-container">
            @forelse($merchants as $merchant)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @if($merchant->logo)
                                    <img src="{{ asset('storage/' . $merchant->logo) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $merchant->company_name }}">
                                @else
                                    <img src="{{ asset('images/default-merchant.jpg') }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $merchant->company_name }}">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $merchant->company_name }}</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ Str::limit($merchant->address, 50) }}
                                    </p>
                                    <p class="card-text">{{ Str::limit($merchant->description, 80) }}</p>
                                    <a href="{{ route('customer.merchants.show', $merchant) }}" class="btn btn-primary btn-sm">View Menu</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center my-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No merchants found</h4>
                        <p>We couldn't find any merchants matching your criteria. Please try different filters.</p>
                        <a href="{{ route('customer.merchants.index') }}" class="btn btn-primary">View All Merchants</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $merchants->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('merchant-search');
        const searchButton = document.getElementById('search-button');
        
        searchButton.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                window.location.href = "{{ route('customer.merchants.index') }}?search=" + encodeURIComponent(searchTerm);
            }
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });
    });
</script>
@endsection