@extends('adminmodule::layouts.master')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Subscription Plans</h1>
                    <p class="text-muted mt-1">Manage all subscription plans</p>
                </div>
                <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Plan
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Plans Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th>Subscriptions</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>
                                <strong>{{ $plan->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($plan->description, 40) }}</small>
                            </td>
                            <td>
                                <strong>${{ number_format($plan->price, 2) }}</strong>
                            </td>
                            <td>
                                {{ $plan->duration_days }} days
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(array_slice($plan->features, 0, 3) as $feature)
                                        <span class="badge bg-info">{{ $feature }}</span>
                                    @endforeach
                                    @if(count($plan->features) > 3)
                                        <span class="badge bg-secondary">+{{ count($plan->features) - 3 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($plan->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $plan->subscriptions()->count() }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $plan->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subscriptions.plans.show', $plan->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.subscriptions.plans.delete', $plan->id) }}" 
                                          method="POST" 
                                          style="display:inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2"></i>
                                    <p class="mt-3">No subscription plans found</p>
                                    <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-sm btn-primary">
                                        Create First Plan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($plans->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $plans->links() }}
        </div>
    @endif
</div>
@endsection
