# Subscription System Quick Reference

## File Locations

### Models
- `app/Models/SubscriptionPlan.php` - Subscription plan definition
- `app/Models/Subscription.php` - User subscription record
- `app/Models/User.php` - Updated with subscription relationships

### Controllers
- `app/Http/Controllers/SubscriptionController.php` - All subscription APIs

### Middleware
- `app/Http/Middleware/CheckSubscription.php` - Verify active subscription
- `app/Http/Middleware/CheckSubscriptionFeature.php` - Verify feature access

### Database
- `database/migrations/2024_01_01_000001_create_subscription_plans_table.php`
- `database/migrations/2024_01_01_000002_create_subscriptions_table.php`
- `database/seeders/SubscriptionPlanSeeder.php`

### Configuration
- `bootstrap/app.php` - Middleware registration

### Routes
- `routes/api.php` - Subscription endpoints

## Quick Start

### 1. Setup Database
```bash
php artisan migrate
php artisan db:seed --class=SubscriptionPlanSeeder
```

### 2. Protect Routes with Subscription
```php
// Require active subscription
Route::middleware('check.subscription')->group(function () {
    Route::get('/premium-trips', [TripController::class, 'premiumTrips']);
});

// Require specific feature
Route::middleware('check.subscription.feature:fleet_management')->group(function () {
    Route::get('/fleet', [FleetController::class, 'index']);
});
```

### 3. Check Subscription in Controller
```php
public function someMethod(Request $request)
{
    $subscription = $request->user()->activeSubscription;
    
    if (!$subscription || !$subscription->hasFeature('feature_name')) {
        return response()->json(['message' => 'Feature not available'], 403);
    }
    
    // Proceed
}
```

## Available Plans (After Seeding)

| Plan | Price | Duration | Features |
|------|-------|----------|----------|
| Basic | $9.99 | 30 days | basic_trips, customer_support, profile_management |
| Professional | $24.99 | 30 days | Basic + priority_trips, advanced_analytics, scheduled_trips |
| Premium | $49.99 | 30 days | Professional + premium_support, fleet_management, vehicle_tracking, revenue_optimization |
| Annual Basic | $99.99 | 365 days | Basic features for a year |
| Annual Professional | $249.99 | 365 days | Professional features for a year |
| Annual Premium | $499.99 | 365 days | Premium features for a year |

## API Examples

### Get Plans
```bash
curl -X GET http://localhost:8000/api/subscriptions/plans
```

### Get Current Subscription
```bash
curl -X GET http://localhost:8000/api/subscriptions/current \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Subscribe to Plan
```bash
curl -X POST http://localhost:8000/api/subscriptions/subscribe \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "plan_id": "PLAN_UUID",
    "payment_method": "credit_card"
  }'
```

### Check Feature
```bash
curl -X POST http://localhost:8000/api/subscriptions/check-feature \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"feature": "premium_support"}'
```

## Relationship Methods

### On User Model
```php
$user->subscriptions()           // All subscriptions (has many)
$user->activeSubscription()      // Current active subscription (has one)
```

### On Subscription Model
```php
$subscription->user()            // Get subscription user
$subscription->plan()            // Get subscription plan
$subscription->isActive()        // Boolean: is subscription active
$subscription->hasFeature($name) // Boolean: does subscription have feature
```

### Queries
```php
// Get active subscriptions
Subscription::active()->get()

// Get expired subscriptions
Subscription::expired()->get()

// Get user's active subscription
$user->subscriptions()->where('status', 'active')->where('expires_at', '>', now())->first()

// Check feature
if ($user->activeSubscription->hasFeature('premium_support')) { /* ... */ }
```

## Status Values

| Status | Meaning |
|--------|---------|
| `active` | Subscription is currently valid |
| `expired` | Subscription period has ended |
| `cancelled` | User cancelled subscription |

## Middleware Aliases

Registered in `bootstrap/app.php`:
```php
'check.subscription' => CheckSubscription::class
'check.subscription.feature' => CheckSubscriptionFeature::class
```

## Error Responses

### No Active Subscription
```json
{
    "success": false,
    "message": "Active subscription required"
}
```
Status: 403

### Feature Not Available
```json
{
    "success": false,
    "message": "Feature 'feature_name' not available in current subscription plan"
}
```
Status: 403

### Unauthorized
```json
{
    "success": false,
    "message": "Unauthorized"
}
```
Status: 401

## Useful Queries

### Get all active subscriptions expiring in 7 days
```php
Subscription::where('status', 'active')
    ->whereBetween('expires_at', [now(), now()->addDays(7)])
    ->with('user', 'plan')
    ->get()
```

### Get revenue by plan
```php
SubscriptionPlan::withCount(['subscriptions' => function ($q) {
    $q->where('status', 'active');
}])->get();
```

### Get user subscription history
```php
$user->subscriptions()->with('plan')->orderBy('created_at', 'desc')->get()
```

### Check if any user has feature
```php
Subscription::active()
    ->whereJsonContains('plan.features', 'fleet_management')
    ->get()
```

## Migration Checklist

Before deploying to production:

1. [ ] Run migrations on development
2. [ ] Test subscription creation
3. [ ] Test subscription expiration
4. [ ] Test feature access
5. [ ] Test middleware protection
6. [ ] Verify API endpoints
7. [ ] Check error handling
8. [ ] Verify database constraints
9. [ ] Test with Postman/API client
10. [ ] Review logs for errors

## Debugging Tips

### Enable query logging
```php
DB::listen(function ($query) {
    \Log::info($query->sql, $query->bindings);
});
```

### Check subscription validity
```php
$sub = $user->activeSubscription;
dump($sub); // Check if null or populated
dump($sub?->expires_at); // Check expiration
dump($sub?->plan->features); // Check features
```

### Test middleware
```php
Route::middleware('check.subscription')->get('/test', function (Request $request) {
    return response()->json(['message' => 'Subscription verified']);
});
```

### Clear caches
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## Next Steps

1. ✅ Create models and migrations
2. ✅ Create middleware
3. ✅ Create controller
4. ✅ Setup routes
5. ✅ Create seeder
6. ⏳ Integrate payment gateway
7. ⏳ Add admin panel
8. ⏳ Create unit tests
9. ⏳ Setup webhooks
10. ⏳ Deploy to production

## Resources

- Full documentation: `SUBSCRIPTION_SYSTEM.md`
- Implementation checklist: `SUBSCRIPTION_IMPLEMENTATION_CHECKLIST.md`
- Laravel Eloquent: https://laravel.com/docs/eloquent
- Laravel Middleware: https://laravel.com/docs/middleware
