<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'description' => 'Perfect for getting started',
                'price' => 9.99,
                'duration_days' => 30,
                'features' => [
                    'basic_trips',
                    'customer_support',
                    'profile_management'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Professional',
                'description' => 'Best for regular users',
                'price' => 24.99,
                'duration_days' => 30,
                'features' => [
                    'basic_trips',
                    'priority_trips',
                    'customer_support',
                    'profile_management',
                    'advanced_analytics',
                    'scheduled_trips'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Premium',
                'description' => 'For professional drivers',
                'price' => 49.99,
                'duration_days' => 30,
                'features' => [
                    'basic_trips',
                    'priority_trips',
                    'premium_support',
                    'profile_management',
                    'advanced_analytics',
                    'scheduled_trips',
                    'fleet_management',
                    'vehicle_tracking',
                    'revenue_optimization'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Annual Basic',
                'description' => 'Annual subscription - Basic plan',
                'price' => 99.99,
                'duration_days' => 365,
                'features' => [
                    'basic_trips',
                    'customer_support',
                    'profile_management'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Annual Professional',
                'description' => 'Annual subscription - Professional plan',
                'price' => 249.99,
                'duration_days' => 365,
                'features' => [
                    'basic_trips',
                    'priority_trips',
                    'customer_support',
                    'profile_management',
                    'advanced_analytics',
                    'scheduled_trips'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Annual Premium',
                'description' => 'Annual subscription - Premium plan',
                'price' => 499.99,
                'duration_days' => 365,
                'features' => [
                    'basic_trips',
                    'priority_trips',
                    'premium_support',
                    'profile_management',
                    'advanced_analytics',
                    'scheduled_trips',
                    'fleet_management',
                    'vehicle_tracking',
                    'revenue_optimization'
                ],
                'is_active' => true
            ]
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
