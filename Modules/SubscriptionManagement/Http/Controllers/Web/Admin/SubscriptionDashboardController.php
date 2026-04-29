<?php

namespace Modules\SubscriptionManagement\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SubscriptionDashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('subscription_view');

        $stats = $this->getStats();

        return view('subscriptionmanagement::admin.dashboard', compact('stats'));
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('subscription_view');

        return response()->json([
            'success' => true,
            'data' => $this->getStats(),
        ]);
    }

    private function getStats(): array
    {
        $activeSubscriptionQuery = Subscription::query()
            ->where('status', 'active')
            ->where('expires_at', '>', now());

        return [
            'total_plans' => SubscriptionPlan::count(),
            'active_plans' => SubscriptionPlan::where('is_active', true)->count(),
            'active_subscriptions' => (clone $activeSubscriptionQuery)->count(),
            'total_subscriptions' => Subscription::count(),
            'expired_subscriptions' => Subscription::query()
                ->where(function ($query) {
                    $query->where('status', 'expired')
                        ->orWhere('expires_at', '<=', now());
                })->count(),
            'cancelled_subscriptions' => Subscription::where('status', 'cancelled')->count(),
            'revenue_total' => (clone $activeSubscriptionQuery)
                ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
                ->sum('subscription_plans.price'),
        ];
    }
}
