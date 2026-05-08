<?php

use Illuminate\Support\Facades\Route;
use Modules\SubscriptionManagement\Http\Controllers\Web\Admin\SubscriptionDashboardController;
use Modules\SubscriptionManagement\Http\Controllers\Web\Admin\SubscriptionPlanController;
use Modules\SubscriptionManagement\Http\Controllers\Web\Admin\UserSubscriptionController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::controller(SubscriptionDashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::get('/statistics', 'statistics')->name('statistics');
        });

        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::controller(SubscriptionPlanController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{id}', 'show')->name('show');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
            });
        });

        Route::group(['prefix' => 'list', 'as' => 'subscriptions.'], function () {
            Route::controller(UserSubscriptionController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/show/{id}', 'show')->name('show');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/update/{id}', 'update')->name('update');

                // Assign subscription plan directly to a driver user
                Route::get('/assign-plan', 'assignPlanToDriverForm')->name('assign_plan_form');
                Route::post('/assign-plan', 'assignPlanToDriver')->name('assign_plan');
            });
        });
    });
});
