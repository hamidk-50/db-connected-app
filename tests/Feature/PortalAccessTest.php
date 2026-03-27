<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'portal.shipping.access',
            'portal.accounting.access',
            'portal.inventory.access',
            'nav.shipping.dashboard.view',
            'nav.shipping.customers.view',
            'nav.accounting.dashboard.view',
            'nav.inventory.dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }

    public function test_sales_user_sees_shipping_and_accounting_tabs(): void
    {
        $role = Role::findOrCreate('sales', 'web');
        $role->syncPermissions([
            'portal.shipping.access',
            'portal.accounting.access',
            'nav.shipping.dashboard.view',
            'nav.accounting.dashboard.view',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Shipping Portal');
        $response->assertSee('Accounting Portal');
        $response->assertDontSee('Inventory Portal');
    }

    public function test_operations_user_sees_shipping_and_inventory_tabs(): void
    {
        $role = Role::findOrCreate('operations', 'web');
        $role->syncPermissions([
            'portal.shipping.access',
            'portal.inventory.access',
            'nav.shipping.dashboard.view',
            'nav.inventory.dashboard.view',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Shipping Portal');
        $response->assertSee('Inventory Portal');
        $response->assertDontSee('Accounting Portal');
    }

    public function test_admin_can_open_shipping_customers_tabbed_view(): void
    {
        $role = Role::findOrCreate('admin', 'web');
        $role->syncPermissions([
            'portal.shipping.access',
            'nav.shipping.dashboard.view',
            'nav.shipping.customers.view',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        Customer::factory()->create(['name' => 'BluePeak Freight']);

        $response = $this->actingAs($user)->get(route('dashboard', [
            'module' => 'shipping',
            'item' => 'customers',
            'subtab' => 'existing',
        ]));

        $response->assertOk();
        $response->assertSee('Existing Customers');
        $response->assertSee('New Customer');
        $response->assertSee('BluePeak Freight');
    }

    public function test_live_customer_search_endpoint_returns_html_results(): void
    {
        $role = Role::findOrCreate('admin', 'web');
        $role->syncPermissions([
            'portal.shipping.access',
            'nav.shipping.dashboard.view',
            'nav.shipping.customers.view',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        Customer::factory()->create(['name' => 'Atlas Shipping']);
        Customer::factory()->create(['name' => 'NorthStar Accounting']);

        $response = $this->actingAs($user)->get(route('dashboard.customers.search', [
            'q' => 'atlas',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['html']);
        $response->assertSee('Atlas Shipping');
        $response->assertDontSee('NorthStar Accounting');
    }
}
