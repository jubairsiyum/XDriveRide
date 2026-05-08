@extends('adminmodule::layouts.master')

@section('title', 'Assign Subscription Plan')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="h3 mb-0">Assign Subscription Plan</h1>
                    <p class="text-muted mt-1">Assign a subscription plan to a driver user</p>
                </div>

                <a href="{{ route('admin.subscriptions.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <form action="{{ route('admin.subscriptions.subscriptions.assign_plan') }}" method="POST" class="card border-0 shadow-sm p-4">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Driver</label>
                    <select class="form-select" name="driver_id" required @if(count($drivers) === 0) disabled @endif>
                        <option value="">{{ count($drivers) === 0 ? 'No drivers found' : 'Select a driver...' }}</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->first_name }} {{ $driver->last_name }} ({{ $driver->email }})
                            </option>
                        @endforeach
                    </select>

                    @if(count($drivers) === 0)
                        <div class="text-muted small mt-2">Create/verify driver users first.</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription Plan</label>
                    <select class="form-select" name="plan_id" required>
                        <option value="">Select a plan...</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">
                                {{ $plan->name }} - ${{ number_format((float) $plan->price, 2) }} / {{ $plan->duration_days }} days
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Assign Plan
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">What happens on assignment?</h5>
                    <ul class="mb-0">
                        <li>The driver’s current <strong>active</strong> subscription is cancelled.</li>
                        <li>A new <strong>active</strong> subscription is created based on selected plan.</li>
                        <li>Premium feature access updates automatically because API middleware checks the driver’s active plan features.</li>
                    </ul>

                    <hr>

                    <p class="text-muted mb-0">
                        Note: cancelling the old active subscription ensures the driver immediately switches to the new plan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
