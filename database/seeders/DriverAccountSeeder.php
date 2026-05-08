<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DriverAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create test drivers
        $drivers = [
            [
                'first_name' => 'John',
                'last_name' => 'Driver',
                'email' => 'driver1@test.com',
                'phone' => '1234567890',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Driver',
                'email' => 'driver2@test.com',
                'phone' => '0987654321',
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Driver',
                'email' => 'driver3@test.com',
                'phone' => '5555555555',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Driver',
                'email' => 'driver4@test.com',
                'phone' => '4444444444',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Driver',
                'email' => 'driver5@test.com',
                'phone' => '3333333333',
            ],
        ];

        foreach ($drivers as $driverData) {
            // Check if driver already exists
            $existingDriver = DB::table('users')->where('email', $driverData['email'])->first();
            
            if (!$existingDriver) {
                $userId = Uuid::uuid4()->toString();

                // Create user
                DB::table('users')->insert([
                    'id' => $userId,
                    'first_name' => $driverData['first_name'],
                    'last_name' => $driverData['last_name'],
                    'email' => $driverData['email'],
                    'phone' => $driverData['phone'],
                    'password' => Hash::make('password123'),
                    'user_type' => 'driver',
                    'is_active' => true,
                    'phone_verified_at' => now(),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create driver details
                DB::table('driver_details')->insert([
                    'user_id' => $userId,
                    'is_online' => false,
                    'availability_status' => 'unavailable',
                    'online_time' => 0,
                    'on_driving_time' => 0,
                    'idle_time' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create user account (wallet)
                DB::table('user_accounts')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'user_id' => $userId,
                    'payable_balance' => 0,
                    'receivable_balance' => 0,
                    'received_balance' => 0,
                    'pending_balance' => 0,
                    'wallet_balance' => 0,
                    'total_withdrawn' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command->info("Driver created: {$driverData['first_name']} {$driverData['last_name']} ({$driverData['email']})");
            } else {
                $this->command->warn("Driver already exists: {$driverData['email']}");
            }
        }

        $this->command->info('Driver accounts seeded successfully!');
    }
}
