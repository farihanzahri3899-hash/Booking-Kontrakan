<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test admin can access admin home page / dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kontrakan.test'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081200000001',
            ]
        );

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Kontrol');
        $response->assertSee('Daftar Pengguna');
    }

    /**
     * Test tenant and normal owner cannot access admin dashboard.
     */
    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $tenant = User::where('role', 'penyewa')->first();
        $this->actingAs($tenant)->get(route('admin.dashboard'))->assertStatus(403);

        $owner = User::where('role', 'pemilik')->first();
        $this->actingAs($owner)->get(route('admin.dashboard'))->assertStatus(403);
    }

    /**
     * Test admin can grant owner permission to a tenant.
     */
    public function test_admin_can_grant_owner_permission_to_tenant(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kontrakan.test'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $tenant = User::create([
            'name' => 'Calon Juragan',
            'email' => 'calonjuragan@example.test',
            'phone' => '081234567899',
            'role' => 'penyewa',
            'password' => bcrypt('password'),
        ]);

        $this->assertEquals('penyewa', $tenant->role);

        $response = $this->actingAs($admin)->post(route('admin.users.grant-owner', $tenant->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('pemilik', $tenant->fresh()->role);
    }

    /**
     * Test admin can revoke owner permission.
     */
    public function test_admin_can_revoke_owner_permission(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kontrakan.test'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $owner = User::create([
            'name' => 'Juragan Mantan',
            'email' => 'juraganmantan@example.test',
            'phone' => '081234567888',
            'role' => 'pemilik',
            'password' => bcrypt('password'),
        ]);

        $this->assertEquals('pemilik', $owner->role);

        $response = $this->actingAs($admin)->post(route('admin.users.revoke-owner', $owner->id));
        $response->assertRedirect();
        $response->assertSessionHas('info');

        $this->assertEquals('penyewa', $owner->fresh()->role);
    }

    /**
     * Test registration always assigns 'penyewa' role and cannot register as 'pemilik'.
     */
    public function test_public_registration_only_creates_tenant(): void
    {
        $registrationData = [
            'name' => 'Pendaftar Baru',
            'email' => 'pendaftarbaru@example.test',
            'phone' => '081987654321',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'pemilik', // Even if submitted maliciously, must be overridden to 'penyewa'
        ];

        $response = $this->post(route('register'), $registrationData);
        $response->assertRedirect(route('home'));

        $user = User::where('email', 'pendaftarbaru@example.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('penyewa', $user->role);
    }

    /**
     * Test that target email farihanzahri3899@gmail.com does not exist.
     */
    public function test_farihan_account_is_completely_deleted(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'farihanzahri3899@gmail.com',
        ]);
    }
}
