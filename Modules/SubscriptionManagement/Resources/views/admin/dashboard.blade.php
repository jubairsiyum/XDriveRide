@extends('adminmodule::layouts.master')

@section('title', 'Subscription Management Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Subscription Management</h1>
                    <p class="text-muted mt-1">Manage subscription plans and user subscriptions</p>
                </div>
                <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Plan
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statisticsContainer">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small">Total Plans</p>
                            <h4 class="mb-0" id="totalPlans">{{ $stats['total_plans'] }}</h4>
                        </div>
                        <div class="badge bg-primary">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small">Active Plans</p>
                            <h4 class="mb-0" id="activePlans">{{ $stats['active_plans'] }}</h4>
                        </div>
                        <div class="badge bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small">Active Subscriptions</p>
                            <h4 class="mb-0" id="activeSubscriptions">{{ $stats['active_subscriptions'] }}</h4>
                        </div>
                        <div class="badge bg-info">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small">Total Subscriptions</p>
                            <h4 class="mb-0" id="totalSubscriptions">{{ $stats['total_subscriptions'] }}</h4>
                        </div>
                        <div class="badge bg-warning">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Breakdown -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2 small">Expired</p>
                    <h4 class="mb-0 text-danger" id="expiredSubscriptions">{{ $stats['expired_subscriptions'] }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2 small">Cancelled</p>
                    <h4 class="mb-0 text-secondary" id="cancelledSubscriptions">{{ $stats['cancelled_subscriptions'] }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2 small">Total Revenue (Active)</p>
                    <h4 class="mb-0 text-success" id="totalRevenue">$0.00</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Plans Management</h5>
                    <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-edit"></i> Manage Plans
                    </a>
                    <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus"></i> Create New Plan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Subscriptions Management</h5>
                    <a href="{{ route('admin.subscriptions.subscriptions.index') }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-list"></i> View All Subscriptions
                    </a>
                    <a href="{{ route('admin.subscriptions.subscriptions.index', ['status' => 'active']) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-filter"></i> Active Only
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    // Load statistics on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadStatistics();
    });

    function loadStatistics() {
        fetch('{{ route("admin.subscriptions.statistics") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                document.getElementById('totalPlans').textContent = stats.total_plans;
                document.getElementById('activePlans').textContent = stats.active_plans;
                document.getElementById('activeSubscriptions').textContent = stats.active_subscriptions;
                document.getElementById('totalSubscriptions').textContent = stats.total_subscriptions;
                document.getElementById('expiredSubscriptions').textContent = stats.expired_subscriptions;
                document.getElementById('cancelledSubscriptions').textContent = stats.cancelled_subscriptions;
                document.getElementById('totalRevenue').textContent = '$' + (stats.revenue_total || 0).toFixed(2);
            }
        })
        .catch(error => console.error('Error loading statistics:', error));
    }

    // Auto-refresh statistics every 30 seconds
    setInterval(loadStatistics, 30000);
</script>
@endpush
@endsection
