@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>My Profile</h2>
        <p class="text-muted">View and manage your personal information</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/default-avatar.jpg') }}" alt="Profile Picture" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Name</p>
                    <p>{{ auth()->user()->name }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Email</p>
                    <p>{{ auth()->user()->email }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Company Name</p>
                    <p>{{ $profile->company_name ?? 'Not specified' }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Phone</p>
                    <p>{{ $profile->phone ?? 'Not specified' }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Address</p>
                    <p>{{ $profile->address ?? 'Not specified' }}</p>
                </div>
                <div class="mb-3">
                    <p class="fw-bold mb-1">Description</p>
                    <p>{{ $profile->description ?? 'Not specified' }}</p>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary w-100">Edit Profile</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Account Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-success text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $orderCount }}</h3>
                                <p class="text-muted">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $completedOrderCount }}</h3>
                                <p class="text-muted">Completed Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning text-center h-100">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $pendingOrderCount }}</h3>
                                <p class="text-muted">Pending Orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                @if(count($recentActivities) > 0)
                <ul class="list-group list-group-flush">
                    @foreach($recentActivities as $activity)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }} me-2"></i>
                                {{ $activity['description'] }}
                            </div>
                            <span class="text-muted">{{ $activity['time'] }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-center">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection