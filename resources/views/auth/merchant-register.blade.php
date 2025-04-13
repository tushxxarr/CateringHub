@extends('layouts.app')

@section('title', 'Register as Merchant')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Register as Merchant</h4>
            </div>
            <div class="card-body">

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.merchant.submit') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Company Name (juga dipakai untuk user.name) --}}
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Business Name</label>
                        <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror"
                            name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control"
                            name="password_confirmation" required>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                            name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Business Address</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                            name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Business Description</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                            name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Logo --}}
                    <div class="mb-3">
                        <label for="logo" class="form-label">Business Logo</label>
                        <input id="logo" type="file" class="form-control @error('logo') is-invalid @enderror"
                            name="logo">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
