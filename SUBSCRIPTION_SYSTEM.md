# Subscription System Implementation Guide

## Overview
A complete subscription management system for the Drivemond application, supporting multiple subscription tiers with feature-based access control.

## Files Created

### Models
- **`app/Models/SubscriptionPlan.php`** - Defines subscription plans with features
- **`app/Models/Subscription.php`** - Manages user subscriptions with active/expired/cancelled states

### Migrations
- **`database/migrations/2024_01_01_000001_create_subscription_plans_table.php`** - Creates subscription_plans table
- **`database/migrations/2024_01_01_000002_create_subscriptions_table.php`** - Creates subscriptions table

### Middleware
- **`app/Http/Middleware/CheckSubscription.php`** - Verifies user has an active subscription
- **`app/Http/Middleware/CheckSubscriptionFeature.php`** - Verifies user's subscription includes specific feature

### Controller
- **`app/Http/Controllers/SubscriptionController.php`** - Handles all subscription operations

### Routes
- **`routes/api.php`** - Added subscription routes

### Seeder
- **`database/seeders/SubscriptionPlanSeeder.php`** - Pre-defined subscription plans

### Configuration
- **`bootstrap/app.php`** - Registered middleware aliases

## Database Setup

### Run Migrations
```bash
php artisan migrate
```

### Seed Subscription Plans
```bash
php artisan db:seed --class=SubscriptionPlanSeeder
```

## API Endpoints

### Get Available Plans
```http
GET /api/subscriptions/plans
```
Response:
```json
{
    "success": true,
    "data": [
        {
            "id": "uuid",
            "name": "Basic",
            "description": "Perfect for getting started",
            "price": 9.99,
            "duration_days": 30,
            "features": ["basic_trips", "customer_support"],
            "is_active": true
        }
    ]
}
```

### Get Current Subscription
```http
GET /api/subscriptions/current
Authorization: Bearer {token}
```
Response:
```json
{
    "success": true,
    "data": {
        "subscription": {
            "id": "uuid",
            "user_id": "uuid",
            "plan_id": "uuid",
            "started_at": "2024-01-01T00:00:00Z",
            "expires_at": "2024-01-31T23:59:59Z",
            "auto_renew": false,
            "status": "active"
        },
        "days_remaining": 30
    }
}
```

### Get Subscription History
```http
GET /api/subscriptions/history
Authorization: Bearer {token}
```

### Subscribe to Plan
```http
POST /api/subscriptions/subscribe
Authorization: Bearer {token}
Content-Type: application/json

{
    "plan_id": "uuid",
    "payment_method": "credit_card"
}
```

### Renew Subscription
```http
POST /api/subscriptions/renew
Authorization: Bearer {token}
```

### Cancel Subscription
```http
POST /api/subscriptions/cancel
Authorization: Bearer {token}
```

### Check Feature Access
```http
POST /api/subscriptions/check-feature
Authorization: Bearer {token}
Content-Type: application/json

{
    "feature": "premium_support"
}
```
Response:
```json
{
    "success": true,
    "has_feature": true,
    "feature": "premium_support"
}
```

## Middleware Usage

### Protect Routes Requiring Subscription
```php
Route::middleware(['auth:sanctum', 'check.subscription'])->group(function () {
    // These routes require an active subscription
    Route::get('/premium-trips', [TripController::class, 'premiumTrips']);
});
```

### Protect Routes Requiring Specific Feature
```php
Route::middleware(['auth:sanctum', 'check.subscription.feature:fleet_management'])->group(function () {
    // These routes require subscription with fleet_management feature
    Route::get('/fleet', [FleetController::class, 'index']);
});
```

## Usage in Controllers

### Check if User has Active Subscription
```php
use App\Models\Subscription;

public function someAction(Request $request)
{
    $subscription = $request->user()->activeSubscription;
    
    if (!$subscription) {
        return response()->json(['message' => 'No active subscription'], 403);
    }
    
    // Proceed with action
}
```

### Check if User has Specific Feature
```php
public function premiumFeature(Request $request)
{
    $subscription = $request->user()->activeSubscription;
    
    if (!$subscription || !$subscription->hasFeature('premium_support')) {
        return response()->json(['message' => 'Feature not available'], 403);
    }
    
    // Use premium feature
}
```

### Get Days Remaining
```php
$subscription = $request->user()->activeSubscription;
$daysRemaining = $subscription->expires_at->diffInDays(now());
```

## Integration with Payment Gateway

The `subscribe()` method in `SubscriptionController` includes a TODO comment for payment processing:

```php
// TODO: Process payment through payment gateway
// TODO: Log transaction
```

To integrate with a payment gateway (e.g., Stripe, PayPal):

1. Add payment processing logic before creating the subscription
2. Only create the subscription if payment is successful
3. Log the transaction for auditing

Example with Stripe:
```php
public function subscribe(Request $request)
{
    $validated = $request->validate([
        'plan_id' => 'required|uuid|exists:subscription_plans,id',
        'payment_method_id' => 'required|string'
    ]);

    $user = Auth::user();
    $plan = SubscriptionPlan::find($validated['plan_id']);

    try {
        // Process payment with Stripe
        $charge = \Stripe\Charge::create([
            'amount' => (int)($plan->price * 100),
            'currency' => 'usd',
            'source' => $validated['payment_method_id'],
            'metadata' => ['user_id' => $user->id, 'plan_id' => $plan->id]
        ]);

        // Create subscription after successful payment
        $subscription = $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'started_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
            'status' => 'active'
        ]);

        // Log transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'subscription',
            'amount' => $plan->price,
            'stripe_charge_id' => $charge->id,
            'subscription_id' => $subscription->id,
            'status' => 'completed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription activated',
            'data' => $subscription->load('plan')
        ]);
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Payment failed',
            'error' => $e->getMessage()
        ], 402);
    }
}
```

## Subscription States

- **active**: Subscription is currently valid and not expired
- **expired**: Subscription has reached its expiration date
- **cancelled**: Subscription was manually cancelled by user

## Features

Available features for different subscription tiers:

### Basic Plan
- `basic_trips` - Standard trip functionality
- `customer_support` - Standard customer support
- `profile_management` - Manage user profile

### Professional Plan
- All Basic features plus:
- `priority_trips` - Priority in trip matching
- `advanced_analytics` - Trip analytics and insights
- `scheduled_trips` - Schedule trips in advance

### Premium Plan
- All Professional features plus:
- `premium_support` - 24/7 premium support
- `fleet_management` - Manage multiple vehicles
- `vehicle_tracking` - Real-time vehicle tracking
- `revenue_optimization` - Revenue optimization tools

## Automatic Renewal (Future Enhancement)

The `auto_renew` field supports automatic subscription renewal. To implement:

1. Create a scheduled job using Laravel's scheduler:
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('subscriptions:auto-renew')->daily();
}
```

2. Create the command:
```php
php artisan make:command RenewSubscriptions
```

3. Implement renewal logic in the command

## Testing

### Test Subscription Creation
```php
$user = User::factory()->create();
$plan = SubscriptionPlan::first();

$subscription = $user->subscriptions()->create([
    'plan_id' => $plan->id,
    'started_at' => now(),
    'expires_at' => now()->addDays(30),
    'status' => 'active'
]);

$this->assertTrue($subscription->isActive());
```

### Test Feature Access
```php
$this->assertTrue($subscription->hasFeature('basic_trips'));
$this->assertFalse($subscription->hasFeature('fleet_management'));
```

## Monitoring

Monitor the following:
- Active subscriptions expiring soon (send renewal reminders)
- Failed payment attempts
- Subscription cancellations (gather feedback)
- Feature usage by subscription tier

## Future Enhancements

1. **Payment Gateway Integration** - Stripe, PayPal, etc.
2. **Automatic Renewal** - Auto-renew active subscriptions
3. **Usage Limits** - Limit features based on subscription (e.g., trips per month)
4. **Discounts & Coupons** - Apply promotional codes
5. **Family Plans** - Share subscriptions among multiple users
6. **Trial Periods** - Offer free trial subscriptions
7. **Billing Portal** - Allow users to manage their subscriptions
8. **Webhook Handling** - Handle payment gateway events
9. **Subscription Analytics** - Track revenue and churn
10. **Referral Program** - Reward users for referrals

## Troubleshooting

### Models not found during migration
Ensure all model imports are correct:
```php
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
```

### Middleware not working
Verify middleware is registered in `bootstrap/app.php`:
```php
'check.subscription' => CheckSubscription::class,
'check.subscription.feature' => CheckSubscriptionFeature::class,
```

### Routes not accessible
Clear route cache:
```bash
php artisan route:clear
```

### Seeder issues
If seeder doesn't work, ensure SubscriptionPlan model is properly imported in the seeder.
