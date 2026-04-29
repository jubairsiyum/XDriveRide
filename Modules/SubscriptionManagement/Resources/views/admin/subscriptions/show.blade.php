@extends('adminmodule::layouts.master')

@section('title', 'Subscription Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Subscription Details</h1>
                <p class="text-muted mt-1">Review driver subscription information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subscriptions.subscriptions.edit', $subscription->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Manage Subscription
                </a>
                <a href="{{ route('admin.subscriptions.subscriptions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Driver Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Name</small>
                            <strong>{{ $subscription->user?->first_name }} {{ $subscription->user?->last_name }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $subscription->user?->email ?: 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Phone</small>
                            <strong>{{ $subscription->user?->phone ?: 'N/A' }}</strong>
                        </div>
                    </div>

                    <hr>

                    <h5 class="card-title mb-3">Plan Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Plan</small>
                            <a href="{{ route('admin.subscriptions.plans.show', $subscription->plan->id) }}">
                                <strong>{{ $subscription->plan->name }}</strong>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Price</small>
                            <strong>${{ number_format($subscription->plan->price, 2) }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Started At</small>
                            <strong>{{ $subscription->started_at?->format('M d, Y h:i A') ?: 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Expires At</small>
                            <strong>{{ $subscription->expires_at?->format('M d, Y h:i A') ?: 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Auto Renew</small>
                            <strong>{{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status</small>
                            @if($subscription->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($subscription->status === 'expired')
                                <span class="badge bg-warning">Expired</span>
                            @elseif($subscription->status === 'cancelled')
                                <span class="badge bg-secondary">Cancelled</span>
                            @else
                                <span class="badge bg-info">{{ ucfirst($subscription->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Time Remaining</h6>
                    @php
                        $daysRemaining = $subscription->expires_at ? now()->diffInDays($subscription->expires_at, false) : null;
                    @endphp

                    @if(is_null($daysRemaining))
                        <p class="text-muted mb-0">Expiration date not set.</p>
                    @elseif($daysRemaining < 0)
                        <div class="alert alert-danger mb-0">Expired {{ abs($daysRemaining) }} day(s) ago.</div>
                    @elseif($daysRemaining <= 7)
                        <div class="alert alert-warning mb-0">Expiring soon: {{ $daysRemaining }} day(s) left.</div>
                    @else
                        <div class="alert alert-success mb-0">{{ $daysRemaining }} day(s) remaining.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
