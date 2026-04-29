@extends('adminmodule::layouts.master')

@section('title', 'Subscription Plan Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Plan Details</h1>
                <p class="text-muted mt-1">View plan configuration and usage</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Plan
                </a>
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $plan->name }}</h5>

                    <p class="text-muted mb-3">{{ $plan->description ?: 'No description provided.' }}</p>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Price</small>
                            <strong>${{ number_format($plan->price, 2) }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Duration</small>
                            <strong>{{ $plan->duration_days }} days</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Status</small>
                            @if($plan->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <h6 class="mb-2">Features</h6>
                    @if(!empty($plan->features) && is_array($plan->features))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($plan->features as $feature)
                                <span class="badge bg-info">{{ $feature }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No features added.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Plan Summary</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Subscriptions</span>
                            <strong>{{ $plan->subscriptions_count }}</strong>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Created</span>
                            <strong>{{ $plan->created_at?->format('M d, Y') }}</strong>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <span class="text-muted">Updated</span>
                            <strong>{{ $plan->updated_at?->format('M d, Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
