@extends('adminmodule::layouts.master')

@section('title', 'Manage Subscription')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('admin.subscriptions.subscriptions.show', $subscription->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <div>
                    <h1 class="h3 mb-0">Manage Subscription</h1>
                    <p class="text-muted mt-1">Extend, change plan, or cancel subscription</p>
                </div>
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
        <div class="col-lg-8">
            <form action="{{ route('admin.subscriptions.subscriptions.update', $subscription->id) }}" method="POST" id="subscriptionForm">
                @csrf
                @method('PUT')

                <!-- Extend Subscription -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="extend" id="actionExtend" checked>
                            <label class="form-check-label" for="actionExtend">
                                <strong>Extend Subscription</strong>
                                <p class="text-muted small mb-0">Add more days to the current subscription</p>
                            </label>
                        </div>

                        <div class="ms-5 mt-3 action-content" id="extendContent">
                            <div class="mb-3">
                                <label for="extension_days" class="form-label">Extension Days</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="extension_days" name="extension_days" 
                                           value="{{ old('extension_days', $subscription->plan->duration_days) }}" 
                                           min="1" placeholder="30">
                                    <span class="input-group-text">days</span>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Current expiration: {{ $subscription->expires_at->format('M d, Y') }}
                                    <br>
                                    New expiration: <strong id="newExpireDate">{{ $subscription->expires_at->copy()->addDays($subscription->plan->duration_days)->format('M d, Y') }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Plan -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="change_plan" id="actionChangePlan">
                            <label class="form-check-label" for="actionChangePlan">
                                <strong>Change Subscription Plan</strong>
                                <p class="text-muted small mb-0">Switch to a different subscription plan</p>
                            </label>
                        </div>

                        <div class="ms-5 mt-3 action-content" id="changePlanContent" style="display: none;">
                            <div class="mb-3">
                                <label for="plan_id" class="form-label">Select New Plan</label>
                                <select class="form-select" id="plan_id" name="plan_id">
                                    <option value="">Choose a plan...</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" 
                                                @if($plan->id === $subscription->plan_id) selected @endif
                                                data-duration="{{ $plan->duration_days }}"
                                                data-price="{{ $plan->price }}">
                                            {{ $plan->name }} - ${{ number_format($plan->price, 2) }} / {{ $plan->duration_days }} days
                                        </option>
                                    @endforeach
                                </select>

                                <div class="card bg-light mt-3" id="planDetails" style="display: none;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Plan Price</small>
                                                <div id="planPrice" class="h6 mb-2">$0.00</div>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Duration</small>
                                                <div id="planDuration" class="h6 mb-2">0 days</div>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            New expiration: <strong id="planExpireDate">N/A</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancel Subscription -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" value="cancel" id="actionCancel"
                                   @if($subscription->status === 'cancelled') disabled @endif>
                            <label class="form-check-label" for="actionCancel">
                                <strong>Cancel Subscription</strong>
                                <p class="text-muted small mb-0">Immediately cancel this subscription</p>
                            </label>
                        </div>

                        <div class="ms-5 mt-3 action-content" id="cancelContent" style="display: none;">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                This action will immediately cancel the subscription. The driver will lose access to premium features.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Apply Changes
                    </button>
                    <a href="{{ route('admin.subscriptions.subscriptions.show', $subscription->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Current Subscription</h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <small class="text-muted">Driver</small>
                            <div>{{ $subscription->user->first_name ?? 'N/A' }} {{ $subscription->user->last_name ?? '' }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Plan</small>
                            <div>{{ $subscription->plan->name }}</div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($subscription->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($subscription->status === 'expired')
                                    <span class="badge bg-warning">Expired</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <small class="text-muted">Expires</small>
                            <div>{{ $subscription->expires_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    const form = document.getElementById('subscriptionForm');
    const actionRadios = document.querySelectorAll('input[name="action"]');
    const actionContents = document.querySelectorAll('.action-content');

    // Toggle action content visibility
    actionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            actionContents.forEach(content => content.style.display = 'none');
            
            if (this.value === 'extend') {
                document.getElementById('extendContent').style.display = 'block';
            } else if (this.value === 'change_plan') {
                document.getElementById('changePlanContent').style.display = 'block';
            } else if (this.value === 'cancel') {
                document.getElementById('cancelContent').style.display = 'block';
            }
        });
    });

    // Update plan details when plan is selected
    const planSelect = document.getElementById('plan_id');
    planSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const duration = option.dataset.duration;
        const price = option.dataset.price;

        if (duration && price) {
            document.getElementById('planPrice').textContent = '$' + parseFloat(price).toFixed(2);
            document.getElementById('planDuration').textContent = duration + ' days';
            
            const expiresAt = new Date();
            expiresAt.setDate(expiresAt.getDate() + parseInt(duration));
            const formatted = expiresAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            document.getElementById('planExpireDate').textContent = formatted;
            
            document.getElementById('planDetails').style.display = 'block';
        } else {
            document.getElementById('planDetails').style.display = 'none';
        }
    });

    // Update new expiration date when extension days change
    const extensionDaysInput = document.getElementById('extension_days');
    extensionDaysInput.addEventListener('change', function() {
        const days = parseInt(this.value) || 0;
        const expiresAt = new Date('{{ $subscription->expires_at?->toIso8601String() }}');
        expiresAt.setDate(expiresAt.getDate() + days);
        const formatted = expiresAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        document.getElementById('newExpireDate').textContent = formatted;
    });

    // Show extend content by default
    document.getElementById('extendContent').style.display = 'block';
</script>
@endpush
@endsection
