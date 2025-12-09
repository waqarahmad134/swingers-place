<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'phone' => '+1234567890',
            'gender' => 'male',
            'profile_type' => 'business',
            'company' => 'Admin Company',
            'website_url' => 'https://example.com',
            'address' => '123 Admin Street, City, State 12345',
            'business_address' => '123 Admin Business Ave, City, State 12345',
        ]);

        // Create regular user with normal profile
        User::create([
            'name' => 'Regular User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'phone' => '+1987654321',
            'gender' => 'male',
            'profile_type' => 'normal',
            'company' => 'Tech Solutions Inc',
            'website_url' => 'https://techsolutions.com',
            'address' => '456 User Lane, City, State 67890',
            'business_address' => '456 Business Park, City, State 67890',
        ]);

        // Create test user with normal profile and company
        User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'phone' => '+1555555555',
            'gender' => 'other',
            'profile_type' => 'normal',
            'company' => 'Digital Marketing Pro',
            'website_url' => 'https://digitalmarketing.com',
            'address' => '789 Test Road, City, State 11111',
            'business_address' => '789 Corporate Plaza, City, State 11111',
        ]);

        

        User::create([
            'name' => 'Mike Wilson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'phone' => '+1444555666',
            'gender' => 'male',
            'profile_type' => 'normal',
            'company' => 'Business Consulting Group',
            'website_url' => 'https://businessconsulting.com',
            'address' => '300 Business Blvd, City, State 34567',
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@gmail.com / password');
        $this->command->info('User: user@gmail.com / password');
        $this->command->info('Test: test@gmail.com / password');
        $this->command->info('Additional public users created for homepage display.');
    }
}
