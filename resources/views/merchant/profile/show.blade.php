@extends('layouts.app')

@section('title', 'Merchant Profile')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Merchant Profile</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <img src="{{ $merchant->profile_image ? asset('storage/' . $merchant->profile_image) : asset('images/default-merchant.jpg') }}" 
                            class="img-fluid rounded mb-3" alt="{{ $merchant->name }}" style="max-height: 250px;">
                        <h4>{{ $merchant->name }}</h4>
                        <p class="text-muted">Member since {{ $merchant->created_at->format('M Y') }}</p>
                        <a href="{{ route('merchant.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="30%">Business Name:</th>
                                        <td>{{ $merchant->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $merchant->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $merchant->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Business Address:</th>
                                        <td>{{ $merchant->business_address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td>{{ $merchant->description ?? 'No description available' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Business License:</th>
                                        <td>
                                            @if($merchant->business_license)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning">Not Uploaded</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Account Status:</th>
                                        <td>
                                            @if($merchant->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($merchant->status == 'pending')
                                                <span class="badge bg-warning">Pending Approval</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Business Performance</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $foodItemsCount ?? 0 }}</h1>
                                <p class="card-text">Food Items</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $totalOrders ?? 0 }}</h1>
                                <p class="card-text">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $averageRating ?? '0.0' }}</h1>
                                <p class="card-text">Average Rating</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <h1 class="display-4">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h1>
                                <p class="card-text">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection