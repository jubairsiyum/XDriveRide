@extends('adminmodule::layouts.master')

@section('title', 'Manage Subscriptions')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Driver Subscriptions</h1>
                    <p class="text-muted mt-1">View and manage all driver subscriptions</p>
                </div>
                <a href="{{ route('admin.subscriptions.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.subscriptions.subscriptions.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label small">Search Driver</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Name, email...">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Plan Filter -->
                        <div class="col-md-3">
                            <label class="form-label small">Plan</label>
                            <select class="form-select" name="plan_id">
                                <option value="">All Plans</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Subscriptions Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Driver</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Started</th>
                        <th>Expires</th>
                        <th>Days Remaining</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        @php
                            $daysRemaining = now()->diffInDays($subscription->expires_at, false);
                            $isExpired = $daysRemaining < 0;
                            $isExpiringSoon = $daysRemaining <= 7 && $daysRemaining > 0;
                        @endphp
                        <tr>
                            <td>
                                <strong>
                                    {{ $subscription->user->first_name ?? 'N/A' }}
                                    {{ $subscription->user->last_name ?? '' }}
                                </strong>
                                <br>
                                <small class="text-muted">{{ $subscription->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.subscriptions.plans.show', $subscription->plan->id) }}">
                                    {{ $subscription->plan->name }}
                                </a>
                            </td>
                            <td>
                                ${{ number_format($subscription->plan->price, 2) }}
                            </td>
                            <td>
                                <small>{{ $subscription->started_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <small>{{ $subscription->expires_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                @if($isExpired)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($isExpiringSoon)
                                    <span class="badge bg-warning">{{ $daysRemaining }} days</span>
                                @else
                                    <span class="badge bg-info">{{ $daysRemaining }} days</span>
                                @endif
                            </td>
                            <td>
                                @if($subscription->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($subscription->status === 'expired')
                                    <span class="badge bg-warning">Expired</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.subscriptions.subscriptions.show', $subscription->id) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.subscriptions.edit', $subscription->id) }}" 
                                       class="btn btn-outline-warning" title="Manage">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2"></i>
                                    <p class="mt-3">No subscriptions found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($subscriptions->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection
