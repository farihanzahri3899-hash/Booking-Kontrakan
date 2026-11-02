<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingKontrakanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Kontrakan');
        $response->assertSee('Cari Kontrakan / Area');
    }

    public function test_property_catalog_displays_properties(): void
    {
        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Kontrakan Tersedia');
    }

    public function test_property_detail_page_loads(): void
    {
        $property = Property::first();
        $this->assertNotNull($property);

        $response = $this->get(route('properties.show', $property->slug));

        $response->assertStatus(200);
        $response->assertSee($property->title);
        $response->assertSee('Fasilitas Kontrakan');
        $response->assertSee('Tata Tertib');
    }

    public function test_quick_login_works_for_tenant_and_owner(): void
    {
        $tenantResponse = $this->get(route('quick-login', 'penyewa'));
        $tenantResponse->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $ownerResponse = $this->get(route('quick-login', 'pemilik'));
        $ownerResponse->assertRedirect(route('owner.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_tenant_can_submit_booking(): void
    {
        $tenant = User::where('role', 'penyewa')->first();
        $property = Property::where('is_available', true)->first();

        $this->actingAs($tenant);

        $bookingData = [
            'start_date' => now()->addDays(2)->toDateString(),
            'duration_months' => 3,
            'occupants_count' => 2,
            'tenant_name' => 'Budi Santoso',
            'tenant_phone' => '081234567890',
            'payment_method' => 'transfer_bca',
            'notes' => 'Siap masuk akhir pekan.',
        ];

        $response = $this->post(route('bookings.store', $property->slug), $bookingData);

        $booking = Booking::where('tenant_phone', '081234567890')->first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('bookings.show', $booking->booking_code));

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $booking->booking_code,
            'tenant_name' => 'Budi Santoso',
            'status' => 'pending',
            'duration_months' => 3,
        ]);
    }

    public function test_property_filter_by_city_works(): void
    {
        $response = $this->get(route('properties.index', ['city' => 'Jakarta Selatan']));

        $response->assertStatus(200);
        $response->assertSee('Jakarta Selatan');
    }

    public function test_unauthenticated_user_cannot_access_booking_form(): void
    {
        $property = Property::first();
        $response = $this->get(route('bookings.create', $property->slug));

        $response->assertRedirect(route('login'));
    }

    public function test_tenant_cannot_access_owner_dashboard(): void
    {
        $tenant = User::where('role', 'penyewa')->first();
        $this->actingAs($tenant);

        $response = $this->get(route('owner.dashboard'));
        $response->assertStatus(403);
    }

    public function test_tenant_can_submit_review(): void
    {
        $tenant = User::where('role', 'penyewa')->first();
        $property = Property::first();
        $this->actingAs($tenant);

        $response = $this->post(route('reviews.store', $property->slug), [
            'rating' => 5,
            'comment' => 'Lingkungan sangat nyaman dan pemilik ramah sekali!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $tenant->id,
            'property_id' => $property->id,
            'rating' => 5,
        ]);
    }

    public function test_owner_can_toggle_property_availability(): void
    {
        $owner = User::where('role', 'pemilik')->first();
        $property = Property::where('user_id', $owner->id)->first();
        $initialState = $property->is_available;

        $this->actingAs($owner);
        $response = $this->post(route('owner.properties.toggle', $property->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'is_available' => ! $initialState,
        ]);
    }

    public function test_authenticated_user_can_view_edit_profile_page(): void
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil');
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '08111111111',
            'role' => 'penyewa',
        ]);
        $this->actingAs($user);

        $response = $this->put(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '08999999999',
            'avatar_url' => 'https://example.com/avatar.jpg',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '08999999999',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);
        $this->actingAs($user);

        $response = $this->put(route('profile.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword456', $user->password));
    }
}
