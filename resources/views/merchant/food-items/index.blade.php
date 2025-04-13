@extends('layouts.app')

@section('title', 'Food Items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Food Items</h2>
    <a href="{{ route('merchant.food-items.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Item
    </a>
</div>

<div class="card">
    <div class="card-header bg-white">
        <form action="{{ route('merchant.food-items.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by name..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="main_course" {{ request('category') == 'main_course' ? 'selected' : '' }}>Main Course</option>
                    <option value="appetizer" {{ request('category') == 'appetizer' ? 'selected' : '' }}>Appetizer</option>
                    <option value="dessert" {{ request('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                    <option value="beverage" {{ request('category') == 'beverage' ? 'selected' : '' }}>Beverage</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($foodItems ?? [] as $item)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                alt="{{ $item->name }}" class="img-thumbnail" width="80">
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>
                            {{ $item->category->name }}
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>
                            @if($item->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('merchant.food-items.edit', $item) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $item->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('merchant.food-items.destroy', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No food items found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $foodItems->links() ?? '' }}
    </div>
</div>
@endsection