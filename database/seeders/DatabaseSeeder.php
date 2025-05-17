<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call AdminUserSeeder to create admin user with roles and permissions
        $this->call(AdminUserSeeder::class);
        
        // Create additional test users if needed
        // User::factory(10)->create();
        
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assignRole('user'); // Assign user role to test user
    }
}
