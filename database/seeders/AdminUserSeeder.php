<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for different sections of the application
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Car permissions
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
            
            // Booking permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            
            // Maintenance permissions
            'view maintenance',
            'create maintenance',
            'edit maintenance',
            'delete maintenance',
            
            // Admin panel access
            'access admin panel',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Create admin role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Create manager role with limited permissions
        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view cars', 'create cars', 'edit cars',
            'view bookings', 'create bookings', 'edit bookings',
            'view maintenance', 'create maintenance', 'edit maintenance',
            'access admin panel'
        ]);
        
        // Create user role with basic permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view cars',
            'create bookings',
            'view bookings',
        ]);
        
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mycar.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // Assign admin role to admin user
        $adminUser->assignRole('admin');
        
        // Create manager user
        $managerUser = User::create([
            'name' => 'Manager User',
            'email' => 'manager@mycar.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // Assign manager role to manager user
        $managerUser->assignRole('manager');
    }
}
