@extends('layouts.app')

@section('title', 'Add Food Item')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Add New Food Item</h2>
            <a href="{{ route('merchant.food-items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Food Item Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('merchant.food-items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Food Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        <div class="form-text">Upload an appetizing image of the food item (JPG, PNG, max 2MB)</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  

                    <div class="mb-3">
                        <div class="form-check">
                            {{-- Hidden input agar "0" tetap dikirim saat tidak dicentang --}}
                            <input type="hidden" name="is_available" value="0">

                            {{-- Checkbox untuk "1" --}}
                            <input
                                class="form-check-input @error('is_available') is-invalid @enderror"
                                type="checkbox"
                                id="is_available"
                                name="is_available"
                                value="1"
                                {{ old('is_available') == '1' ? 'checked' : '' }}>
                                
                            <label class="form-check-label" for="is_available">
                                Available for Order
                            </label>

                            @error('is_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Food Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection