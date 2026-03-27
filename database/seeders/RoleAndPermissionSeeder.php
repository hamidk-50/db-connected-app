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

        $basePermissions = [
            'view dashboard',
            'manage users',
            'manage operations',
            'manage sales',
            'manage customers',
            'manage mailboxes',
        ];
        $portalPermissions = [
            'portal.shipping.access',
            'portal.accounting.access',
            'portal.inventory.access',
        ];
        $shippingNavPermissions = [
            'nav.shipping.dashboard.view',
            'nav.shipping.shipments.view',
            'nav.shipping.rate_quotes.view',
            'nav.shipping.tracking.view',
            'nav.shipping.returns.view',
            'nav.shipping.customers.view',
        ];
        $accountingNavPermissions = [
            'nav.accounting.dashboard.view',
            'nav.accounting.invoices.view',
            'nav.accounting.bills.view',
            'nav.accounting.payments.view',
            'nav.accounting.reconciliation.view',
            'nav.accounting.reports.view',
        ];
        $inventoryNavPermissions = [
            'nav.inventory.dashboard.view',
            'nav.inventory.stock_overview.view',
            'nav.inventory.products.view',
            'nav.inventory.purchase_orders.view',
            'nav.inventory.adjustments.view',
            'nav.inventory.cycle_counts.view',
        ];

        $permissions = array_merge(
            $basePermissions,
            $portalPermissions,
            $shippingNavPermissions,
            $accountingNavPermissions,
            $inventoryNavPermissions,
        );

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $operationsRole = Role::findOrCreate('operations', 'web');
        $salesRole = Role::findOrCreate('sales', 'web');

        $adminRole->syncPermissions(Permission::all());
        $operationsRole->syncPermissions(array_merge([
            'view dashboard',
            'manage operations',
            'manage customers',
            'manage mailboxes',
            'portal.shipping.access',
            'portal.inventory.access',
        ], $shippingNavPermissions, $inventoryNavPermissions));
        $salesRole->syncPermissions(array_merge([
            'view dashboard',
            'manage sales',
            'manage customers',
            'portal.shipping.access',
            'portal.accounting.access',
        ], [
            'nav.shipping.dashboard.view',
            'nav.shipping.shipments.view',
            'nav.shipping.rate_quotes.view',
            'nav.shipping.tracking.view',
            'nav.shipping.customers.view',
        ], [
            'nav.accounting.dashboard.view',
            'nav.accounting.invoices.view',
            'nav.accounting.payments.view',
            'nav.accounting.reports.view',
        ]));

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
