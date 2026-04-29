<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    */

    App\Providers\AppServiceProvider::class,
    App\Providers\GlobalDataServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    
    // Module Route Providers
    \Modules\AdminModule\Providers\RouteServiceProvider::class,
    \Modules\AiModule\Providers\RouteServiceProvider::class,
    \Modules\AuthManagement\Providers\RouteServiceProvider::class,
    \Modules\BlogManagement\Providers\RouteServiceProvider::class,
    \Modules\BusinessManagement\Providers\RouteServiceProvider::class,
    \Modules\ChattingManagement\Providers\RouteServiceProvider::class,
    \Modules\FareManagement\Providers\RouteServiceProvider::class,
    \Modules\Gateways\Providers\RouteServiceProvider::class,
    \Modules\ParcelManagement\Providers\RouteServiceProvider::class,
    \Modules\PromotionManagement\Providers\RouteServiceProvider::class,
    \Modules\ReviewModule\Providers\RouteServiceProvider::class,
    \Modules\TransactionManagement\Providers\RouteServiceProvider::class,
    \Modules\TripManagement\Providers\RouteServiceProvider::class,
    \Modules\UserManagement\Providers\RouteServiceProvider::class,
    \Modules\VehicleManagement\Providers\RouteServiceProvider::class,
    \Modules\ZoneManagement\Providers\RouteServiceProvider::class,
    
    // Module Repository Providers
    \Modules\AdminModule\Providers\RepositoryServiceProvider::class,
    \Modules\AiModule\Providers\RepositoryServiceProvider::class,
    \Modules\BlogManagement\Providers\RepositoryServiceProvider::class,
    \Modules\AuthManagement\Providers\RepositoryServiceProvider::class,
    \Modules\BusinessManagement\Providers\RepositoryServiceProvider::class,
    \Modules\ChattingManagement\Providers\RepositoryServiceProvider::class,
    \Modules\FareManagement\Providers\RepositoryServiceProvider::class,
    \Modules\ParcelManagement\Providers\RepositoryServiceProvider::class,
    \Modules\PromotionManagement\Providers\RepositoryServiceProvider::class,
    \Modules\ReviewModule\Providers\RepositoryServiceProvider::class,
    \Modules\TransactionManagement\Providers\RepositoryServiceProvider::class,
    \Modules\TripManagement\Providers\RepositoryServiceProvider::class,
    \Modules\UserManagement\Providers\RepositoryServiceProvider::class,
    \Modules\ZoneManagement\Providers\RepositoryServiceProvider::class,
    \Modules\VehicleManagement\Providers\RepositoryServiceProvider::class,
    \Modules\AdminModule\Providers\FirebaseServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Third-Party Service Providers
    |--------------------------------------------------------------------------
    */

    Matanyadaev\LaravelEloquentSpatial\EloquentSpatialServiceProvider::class,

];
