<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $activeSubscription = $request->user()->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        if (!$activeSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'Active subscription required'
            ], 403);
        }

        if (!$activeSubscription->hasFeature($feature)) {
            return response()->json([
                'success' => false,
                'message' => "Feature '$feature' not available in current subscription plan"
            ], 403);
        }

        $request->attributes->set('subscription', $activeSubscription);

        return $next($request);
    }
}
