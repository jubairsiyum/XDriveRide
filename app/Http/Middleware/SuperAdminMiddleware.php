<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type === 'super-admin') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only super administrators can access this resource.',
                'code' => 'SUPER_ADMIN_REQUIRED',
            ], 403);
        }

        return redirect()->route('admin.auth.login')
            ->with('error', 'You do not have permission to access this area.');
    }
}
