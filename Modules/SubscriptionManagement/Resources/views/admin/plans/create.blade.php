@extends('adminmodule::layouts.master')

@section('title', 'Create Subscription Plan')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Create New Subscription Plan</h1>
            <p class="text-muted mt-1">Add a new subscription plan to the system</p>
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
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.plans.store') }}" method="POST">
                        @csrf

                        <!-- Plan Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., Basic, Premium, Enterprise" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe this subscription plan" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Price -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" 
                                       placeholder="9.99" step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration Days -->
                            <div class="col-md-6 mb-3">
                                <label for="duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                       id="duration_days" name="duration_days" value="{{ old('duration_days') }}" 
                                       placeholder="30" min="1" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mb-3">
                            <label for="features" class="form-label">Features <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-2">Add features for this plan (one per line)</p>
                            <textarea class="form-control @error('features') is-invalid @enderror" 
                                      id="features" name="features" rows="5" 
                                      placeholder="Unlimited trips&#10;Premium support&#10;Advanced analytics"
                                      required>{{ old('features', "Unlimited trips\nPremium support") }}</textarea>
                            <small class="text-muted">Enter each feature on a new line</small>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Make this plan active immediately
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Plan
                            </button>
                            <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-question-circle text-info"></i> Tips
                    </h5>
                    <ul class="small text-muted">
                        <li class="mb-2">Use descriptive names for your plans so users understand the difference</li>
                        <li class="mb-2">Price should match your business model and market research</li>
                        <li class="mb-2">Duration defines how long the subscription is valid</li>
                        <li class="mb-2">Add 3-5 key features that differentiate this plan</li>
                        <li class="mb-2">You can deactivate plans later without deleting them</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    // Auto-format textarea input for features
    document.getElementById('features').addEventListener('input', function() {
        const lines = this.value.split('\n').filter(line => line.trim());
        this.value = lines.join('\n');
    });
</script>
@endpush
@endsection
