<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Get all available subscription plans
     */
    public function getPlans()
    {
        try {
            $plans = SubscriptionPlan::where('is_active', true)->get();

            return response()->json([
                'success' => true,
                'data' => $plans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscription plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's current active subscription
     */
    public function getCurrentSubscription()
    {
        try {
            $user = Auth::user();

            $subscription = $user->subscriptions()
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->with('plan')
                ->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'subscription' => $subscription,
                    'days_remaining' => $subscription->expires_at->diffInDays(now())
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's subscription history
     */
    public function getHistory()
    {
        try {
            $user = Auth::user();

            $subscriptions = $user->subscriptions()
                ->with('plan')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $subscriptions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subscription history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscribe user to a plan (payment gateway would be integrated here)
     */
    public function subscribe(Request $request)
    {
        try {
            $validated = $request->validate([
                'plan_id' => 'required|exists:subscription_plans,id',
                'payment_method' => 'required|string'
            ]);

            $user = Auth::user();
            $plan = SubscriptionPlan::find($validated['plan_id']);

            // Cancel any existing active subscription (for this driver)
            $user->subscriptions()
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'auto_renew' => false,
                ]);

            // Create new subscription
            $subscription = $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'started_at' => now(),
                'expires_at' => now()->addDays((int) $plan->duration_days),
                'auto_renew' => false,
                'status' => 'active'
            ]);

            // TODO: Process payment through payment gateway
            // TODO: Log transaction

            return response()->json([
                'success' => true,
                'message' => 'Subscription activated successfully',
                'data' => [
                    'subscription' => $subscription->load('plan'),
                    'expires_at' => $subscription->expires_at
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renew subscription
     */
    public function renew(Request $request)
    {
        try {
            $user = Auth::user();

            // Mirror admin "extend/renew" logic: extend existing active subscription
            $currentSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->with('plan')
                ->first();

            if (!$currentSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription to renew'
                ], 404);
            }

            $plan = $currentSubscription->plan;

            $baseDate = $currentSubscription->expires_at && $currentSubscription->expires_at->greaterThan(now())
                ? $currentSubscription->expires_at->copy()
                : now();

            $currentSubscription->expires_at = $baseDate->addDays((int) $plan->duration_days);
            $currentSubscription->status = 'active';
            $currentSubscription->auto_renew = false;

            $currentSubscription->save();

            return response()->json([
                'success' => true,
                'message' => 'Subscription renewed successfully',
                'data' => $currentSubscription->fresh()->load('plan')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to renew subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        try {
            $user = Auth::user();

            $subscription = $user->subscriptions()
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription to cancel'
                ], 404);
            }

            // Mirror admin cancel: status=cancelled and auto_renew=false
            $subscription->update([
                'status' => 'cancelled',
                'auto_renew' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has a specific feature
     */
    public function hasFeature(Request $request)
    {
        try {
            $validated = $request->validate([
                'feature' => 'required|string'
            ]);

            $user = Auth::user();
            $subscription = $user->subscriptions()
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->with('plan')
                ->first();

            if (!$subscription) {
                return response()->json([
                    'success' => true,
                    'has_feature' => false,
                    'message' => 'No active subscription'
                ]);
            }

            $hasFeature = $subscription->hasFeature($validated['feature']);

            return response()->json([
                'success' => true,
                'has_feature' => $hasFeature,
                'feature' => $validated['feature']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check feature',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
