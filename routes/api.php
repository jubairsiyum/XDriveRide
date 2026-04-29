<?php

use App\Http\Controllers\RealTimeLocationSharingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('track/{token}/data', [RealTimeLocationSharingController::class, 'updatePolyline'])->name('track.data');

// Subscription routes
Route::prefix('subscriptions')->group(function () {
    // Public routes
    Route::get('/plans', [SubscriptionController::class, 'getPlans'])->name('subscriptions.plans');

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/current', [SubscriptionController::class, 'getCurrentSubscription'])->name('subscriptions.current');
        Route::get('/history', [SubscriptionController::class, 'getHistory'])->name('subscriptions.history');
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
        Route::post('/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('/check-feature', [SubscriptionController::class, 'hasFeature'])->name('subscriptions.check-feature');
    });
});

