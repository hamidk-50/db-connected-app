<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
    }

    public function test_guest_is_redirected_to_login_for_customers_page(): void
    {
        $this->get(route('customers.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_without_role_cannot_access_customers_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('customers.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_customer(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->post(route('customers.store'), [
                'name' => 'Acme Corp',
                'email' => 'acme@example.com',
                'phone' => '555-111-2222',
                'status' => 'prospect',
            ])
            ->assertRedirect(route('customers.index'));

        $customer = Customer::where('email', 'acme@example.com')->firstOrFail();

        $this->assertDatabaseHas('customers', [
            'name' => 'Acme Corp',
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->patch(route('customers.update', $customer), [
                'name' => 'Acme Corporation',
                'email' => 'acme@example.com',
                'phone' => '555-111-3333',
                'status' => 'active',
            ])
            ->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Acme Corporation',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->delete(route('customers.destroy', $customer))
            ->assertRedirect(route('customers.index'));

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_customer_mutations_redirect_to_return_to_when_supplied(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $returnTo = route('dashboard', [
            'module' => 'shipping',
            'item' => 'customers',
            'subtab' => 'existing',
        ]);

        $this->actingAs($user)
            ->post(route('customers.store'), [
                'name' => 'Nexa Logistics',
                'email' => 'nexa@example.com',
                'phone' => '555-888-1111',
                'status' => 'prospect',
                'return_to' => $returnTo,
            ])
            ->assertRedirect($returnTo);

        $customer = Customer::where('email', 'nexa@example.com')->firstOrFail();

        $this->actingAs($user)
            ->patch(route('customers.update', $customer), [
                'name' => 'Nexa Logistics Ltd',
                'email' => 'nexa@example.com',
                'phone' => '555-888-2222',
                'status' => 'active',
                'return_to' => $returnTo,
            ])
            ->assertRedirect($returnTo);

        $this->actingAs($user)
            ->delete(route('customers.destroy', $customer), [
                'return_to' => $returnTo,
            ])
            ->assertRedirect($returnTo);
    }
}
