<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AllUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin User
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@admin.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'super-admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin Employee User
        DB::table('users')->updateOrInsert(
            ['email' => 'admin-employee@admin.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'Admin',
                'last_name' => 'Employee',
                'email' => 'admin-employee@admin.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'admin-employee',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Customer User (Passenger)
        DB::table('users')->updateOrInsert(
            ['email' => 'customer@test.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'John',
                'last_name' => 'Customer',
                'email' => 'customer@test.com',
                'password' => Hash::make('12345678'),
                'phone' => '+1234567890',
                'user_type' => 'customer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Driver User
        DB::table('users')->updateOrInsert(
            ['email' => 'driver@test.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'Mike',
                'last_name' => 'Driver',
                'email' => 'driver@test.com',
                'password' => Hash::make('12345678'),
                'phone' => '+9876543210',
                'user_type' => 'driver',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Additional test users
        DB::table('users')->updateOrInsert(
            ['email' => 'customer2@test.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'Jane',
                'last_name' => 'Passenger',
                'email' => 'customer2@test.com',
                'password' => Hash::make('12345678'),
                'phone' => '+1111111111',
                'user_type' => 'customer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'driver2@test.com'],
            [
                'id' => Uuid::uuid4(),
                'first_name' => 'Tom',
                'last_name' => 'Cabbie',
                'email' => 'driver2@test.com',
                'password' => Hash::make('12345678'),
                'phone' => '+2222222222',
                'user_type' => 'driver',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
