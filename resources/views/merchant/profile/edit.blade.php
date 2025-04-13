@extends('layouts.app')

@section('title', 'Edit Merchant Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Merchant Profile</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('merchant.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-12 text-center mb-4">
                            <img src="{{ $merchant->profile_image ? asset('storage/' . $merchant->profile_image) : asset('images/default-merchant.jpg') }}" 
                                class="img-thumbnail mb-2" alt="{{ $merchant->name }}" style="max-height: 200px;">
                            <div class="mt-2">
                                <label for="profile_image" class="form-label">Change Profile Image</label>
                                <input id="profile_image" type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image">
                                @error('profile_image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Business Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $merchant->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $merchant->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $merchant->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="business_address" class="form-label">Business Address</label>
                            <textarea id="business_address" class="form-control @error('business_address') is-invalid @enderror" name="business_address" required>{{ old('business_address', $merchant->business_address) }}</textarea>
                            @error('business_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Business Description</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $merchant->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="business_license" class="form-label">Update Business License</label>
                            <input id="business_license" type="file" class="form-control @error('business_license') is-invalid @enderror" name="business_license">
                            @error('business_license')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            @if($merchant->business_license)
                                <small class="form-text text-success">Business license has been uploaded.</small>
                            @else
                                <small class="form-text text-danger">No business license uploaded.</small>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('merchant.profile.show') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection