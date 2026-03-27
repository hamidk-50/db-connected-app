<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Seed roles, permissions, and starter users.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage operations',
            'manage sales',
            'manage customers',
            'manage mailboxes',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $operationsRole = Role::findOrCreate('operations', 'web');
        $salesRole = Role::findOrCreate('sales', 'web');

        $adminRole->syncPermissions(Permission::all());
        $operationsRole->syncPermissions([
            'view dashboard',
            'manage operations',
            'manage customers',
            'manage mailboxes',
        ]);
        $salesRole->syncPermissions([
            'view dashboard',
            'manage sales',
            'manage customers',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'ChangeMe123!',
                'email_verified_at' => now(),
            ],
        );
        $adminUser->syncRoles([$adminRole->name]);

        $operationsUser = User::firstOrCreate(
            ['email' => 'ops@example.com'],
            [
                'name' => 'Operations User',
                'password' => 'ChangeMe123!',
                'email_verified_at' => now(),
            ],
        );
        $operationsUser->syncRoles([$operationsRole->name]);

        $salesUser = User::firstOrCreate(
            ['email' => 'sales@example.com'],
            [
                'name' => 'Sales User',
                'password' => 'ChangeMe123!',
                'email_verified_at' => now(),
            ],
        );
        $salesUser->syncRoles([$salesRole->name]);

        if (Customer::count() === 0) {
            Customer::factory()->count(8)->create([
                'created_by' => $adminUser->id,
            ]);

            Customer::factory()->create([
                'name' => 'Northwind Distribution',
                'email' => 'contact@northwind.example',
                'phone' => '555-120-4500',
                'status' => 'active',
                'created_by' => $salesUser->id,
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
