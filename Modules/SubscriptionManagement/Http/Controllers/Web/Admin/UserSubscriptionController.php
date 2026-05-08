<?php

namespace Modules\SubscriptionManagement\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserSubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('subscription_view');

        $query = Subscription::query()
            ->with(['user', 'plan'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->integer('plan_id'));
        }

        if ($request->filled('status')) {
            $status = (string) $request->input('status');
            if ($status === 'active') {
                $query->where('status', 'active')->where('expires_at', '>', now());
            } elseif ($status === 'expired') {
                $query->where(function ($expiredQuery) {
                    $expiredQuery->where('status', 'expired')
                        ->orWhere('expires_at', '<=', now());
                });
            } elseif ($status === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        }

        $subscriptions = $query->paginate(function_exists('paginationLimit') ? paginationLimit() : 15)
            ->appends($request->query());

        $plans = SubscriptionPlan::query()->orderBy('name')->get(['id', 'name']);

        return view('subscriptionmanagement::admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function show(string $id): View
    {
        $this->authorize('subscription_view');

        $subscription = Subscription::query()
            ->with(['user', 'plan'])
            ->findOrFail($id);

        return view('subscriptionmanagement::admin.subscriptions.show', compact('subscription'));
    }

    public function edit(string $id): View
    {
        $this->authorize('subscription_edit');

        $subscription = Subscription::query()
            ->with(['user', 'plan'])
            ->findOrFail($id);

        $plans = SubscriptionPlan::query()->where('is_active', true)->orderBy('name')->get();

        return view('subscriptionmanagement::admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $this->authorize('subscription_edit');

        $subscription = Subscription::query()
            ->with('plan')
            ->findOrFail($id);

        $action = (string) $request->input('action', 'extend');

        if ($action === 'extend') {
            $validated = $request->validate([
                'extension_days' => 'required|integer|min:1|max:3650',
            ]);

            $baseDate = $subscription->expires_at && $subscription->expires_at->greaterThan(now())
                ? $subscription->expires_at->copy()
                : now();

            $subscription->expires_at = $baseDate->addDays($validated['extension_days']);
            $subscription->status = 'active';
        } elseif ($action === 'change_plan') {
            $validated = $request->validate([
                'plan_id' => 'required|exists:subscription_plans,id',
            ]);

            $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

            $subscription->plan_id = $plan->id;
            $subscription->started_at = now();
            $subscription->expires_at = Carbon::now()->addDays((int) $plan->duration_days);
            $subscription->status = 'active';
        } elseif ($action === 'cancel') {
            $subscription->status = 'cancelled';
            $subscription->auto_renew = false;
        } else {
            return back()->with('error', 'Invalid subscription action selected.');
        }

        $subscription->save();

        return redirect()
            ->route('admin.subscriptions.subscriptions.show', $subscription->id)
            ->with('success', 'Subscription updated successfully.');
    }

    public function assignPlanToDriverForm(): View
    {
        $this->authorize('subscription_add');

        $drivers = User::query()
            ->where('user_type', 'driver')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'email', 'phone']);

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('subscriptionmanagement::admin.subscriptions.assign-plan', compact('drivers', 'plans'));
    }

    public function assignPlanToDriver(Request $request): RedirectResponse
    {
        $this->authorize('subscription_add');

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'plan_id' => 'required|integer|exists:subscription_plans,id',
        ]);

        $driver = User::query()
            ->where('user_type', 'driver')
            ->findOrFail($validated['driver_id']);

        $plan = SubscriptionPlan::query()
            ->where('is_active', true)
            ->findOrFail($validated['plan_id']);

        // Cancel any existing active subscription (for this driver)
        $driver->subscriptions()
            ->where('status', 'active')
            ->update([
                'status' => 'cancelled',
                'auto_renew' => false,
            ]);

        $subscription = $driver->subscriptions()->create([
            'plan_id' => $plan->id,
            'started_at' => now(),
            'expires_at' => Carbon::now()->addDays((int) $plan->duration_days),
            'auto_renew' => false,
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.subscriptions.subscriptions.show', $subscription->id)
            ->with('success', 'Subscription plan assigned to driver successfully.');
    }
}
