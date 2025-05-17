# My Car Laravel Application Documentation

## Table of Contents
- [Project Overview](#project-overview)
- [Installation](#installation)
- [Packages and Technologies](#packages-and-technologies)
- [Project Structure](#project-structure)
- [Authentication](#authentication)
- [Role and Permission Management](#role-and-permission-management)
- [Admin Panel](#admin-panel)
- [Development Guidelines](#development-guidelines)

## Project Overview

This Laravel-based application is designed to manage car rentals and bookings. It includes a comprehensive authentication system, role-based access control, and an admin dashboard for efficient management.

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL or another supported database

### Setup Steps

1. Clone the repository
```bash
git clone <repository-url>
cd my_car_laravel
```

2. Install PHP dependencies
```bash
composer install
```

3. Install and build frontend assets
```bash
npm install
npm run build
```

4. Set up environment file
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in the `.env` file

6. Run migrations
```bash
php artisan migrate
```

7. Start the development server
```bash
php artisan serve
```

## Packages and Technologies

### Laravel Breeze (v2.3.6)
Laravel Breeze provides a minimal and simple authentication implementation for Laravel applications. In this project, it's configured with:
- Blade templates with Alpine.js
- Dark mode support
- Authentication scaffolding (login, registration, password reset)
- Profile management

#### Configuration
Breeze's configuration is minimal and primarily exists in the published views, controllers, and routes:
- Authentication views: `resources/views/auth`
- Profile views: `resources/views/profile`
- Authentication controllers: `app/Http/Controllers/Auth`

### Spatie Laravel Permission (v6.18.0)
Spatie's Laravel Permission package provides a solution for managing roles and permissions in Laravel applications. It offers:
- Role-based access control
- Permission assignment to roles
- Direct permission assignment to users
- Permission and role checking middleware

#### Configuration
The package's configuration is located at `config/permission.php`, which defines:
- Cache settings
- Table names
- Permission and role models
- Team support settings

#### Database Tables
The package creates several tables:
- `permissions`: Stores individual permissions
- `roles`: Stores role definitions
- `role_has_permissions`: Maps permissions to roles
- `model_has_roles`: Maps roles to users (or other models)
- `model_has_permissions`: Maps permissions directly to users (or other models)

### Filament Admin (v3.3.14)
Filament provides a robust admin panel with rapid CRUD generation, custom pages, and dashboards. It's built on:
- Livewire
- Tailwind CSS
- Alpine.js

#### Configuration
Filament's core configuration is located at:
- `app/Providers/Filament/AdminPanelProvider.php`: Main panel configuration
- Resources are stored in the `app/Filament/Resources` directory
- Widgets are stored in the `app/Filament/Widgets` directory
- Pages are stored in the `app/Filament/Pages` directory

## Project Structure

```
my_car_laravel/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Filament/
│   │   ├── Pages/
│   │   ├── Resources/
│   │   └── Widgets/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Providers/
│       └── Filament/
│           └── AdminPanelProvider.php
├── config/
│   ├── filament.php
│   └── permission.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       └── auth/
├── routes/
└── storage/
```

## Authentication

The application uses Laravel Breeze for authentication, which provides:

### Features
- User registration
- User login/logout
- Password reset
- Email verification
- Remember me functionality
- Profile management

### Routes
- `/register` - User registration
- `/login` - User login
- `/forgot-password` - Password recovery
- `/reset-password` - Reset password form
- `/verify-email` - Email verification
- `/profile` - User profile management

### Customization
To customize authentication views, edit files in the `resources/views/auth` directory. For behavior modifications, the controllers are in `app/Http/Controllers/Auth`.

## Role and Permission Management

This application uses Spatie Laravel Permission to implement a robust role-based access control system.

### Basic Usage

#### Creating Roles and Permissions
```php
// Creating permissions
Permission::create(['name' => 'create cars']);
Permission::create(['name' => 'edit cars']);
Permission::create(['name' => 'delete cars']);

// Creating roles
$adminRole = Role::create(['name' => 'admin']);
$managerRole = Role::create(['name' => 'manager']);

// Assigning permissions to roles
$adminRole->givePermissionTo('create cars', 'edit cars', 'delete cars');
$managerRole->givePermissionTo('create cars', 'edit cars');
```

#### Assigning Roles to Users
```php
// Assign a role to a user
$user->assignRole('admin');

// Check if user has a role
if ($user->hasRole('admin')) {
    // User has admin role
}

// Check if user has any permissions
if ($user->hasPermissionTo('edit cars')) {
    // User can edit cars
}
```

### Middleware

The package provides middleware for controlling route access:
- `role`: Requires the user to have a specific role
- `permission`: Requires the user to have a specific permission
- `role_or_permission`: Requires the user to have either a specific role or a specific permission

Example usage in routes:
```php
Route::middleware(['role:admin'])->group(function () {
    // Routes for admin users
});

Route::middleware(['permission:edit cars'])->group(function () {
    // Routes for users who can edit cars
});
```

## Admin Panel

Filament provides a powerful CRUD builder and admin panel for the application.

### Access
The admin panel is accessible at `/admin` by default. Users must have appropriate permissions to access different sections of the admin panel.

### Resources

Filament resources represent the CRUD interfaces for your models. They are located in `app/Filament/Resources`.

#### Creating a Resource

To create a new resource for a model:
```bash
php artisan make:filament-resource Car
```

This will generate various files including:
- `app/Filament/Resources/CarResource.php`
- `app/Filament/Resources/CarResource/Pages/*.php`

#### Resource Components

Each resource typically contains:
- Form schemas (for create and edit pages)
- Table definitions (for index pages)
- Filter definitions (for filtering data in tables)
- Action definitions (for row and bulk actions)

### Widgets

Widgets can be added to the dashboard or resource pages. They are located in `app/Filament/Widgets`.

To create a widget:
```bash
php artisan make:filament-widget StatsOverview
```

### Pages

Custom admin pages can be created using:
```bash
php artisan make:filament-page Settings
```

### Integration with Spatie Permissions

The admin panel is integrated with Spatie Permissions to restrict access to resources based on user roles and permissions.

In `AdminPanelProvider.php`, the authentication guard and authorization checks are configured:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->authGuard('web')
        ->authMiddleware([
            Authenticate::class,
        ]);
}
```

## Development Guidelines

### Extending the Authentication System

1. If you need to customize the login logic, modify the `app/Http/Controllers/Auth/AuthenticatedSessionController.php` file.
2. For registration changes, modify the `app/Http/Controllers/Auth/RegisteredUserController.php` file.

### Adding New Roles and Permissions

When adding new roles and permissions, consider using database seeders:

```php
// Create a seeder
php artisan make:seeder RolesAndPermissionsSeeder

// Inside the seeder
public function run()
{
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    Permission::create(['name' => 'manage bookings']);
    
    // Create roles and assign permissions
    $adminRole = Role::create(['name' => 'admin']);
    $adminRole->givePermissionTo(Permission::all());
}
```

### Creating Admin Resources

When creating new resources for the admin panel, follow these steps:

1. Generate the resource:
```bash
php artisan make:filament-resource YourModel
```

2. Define the form fields, table columns, and relationships.

3. Implement any custom actions or filters as needed.

4. Apply permission checks to control access:
```php
protected static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

public static function canViewAny(): bool
{
    return auth()->user()->can('view_your_models');
}
```

### Best Practices

1. **Authorization**: Always use the permission system rather than hard-coding user IDs or roles.
2. **Form Validation**: Use Laravel's validation features in all form requests.
3. **Resource Organization**: Keep Filament resources organized by domain or feature.
4. **Custom Actions**: Leverage Filament's action system for complex operations.

---

This documentation is a living document and should be updated as the project evolves.

