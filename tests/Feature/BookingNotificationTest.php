<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BookingNotificationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test notification is sent to property owner for each property type.
     */
    public function test_owner_receives_notification_when_property_is_booked(): void
    {
        Notification::fake();

        $owner = User::where('role', 'pemilik')->first();
        $tenant = User::where('role', 'penyewa')->first();
        $property = Property::where('user_id', $owner->id)->where('is_available', true)->first();

        $this->actingAs($tenant);

        $bookingData = [
            'start_date' => now()->addDays(3)->toDateString(),
            'duration_months' => 6,
            'occupants_count' => 2,
            'tenant_name' => 'Eko Prasetyo',
            'tenant_phone' => '089876543210',
            'payment_method' => 'qris',
            'notes' => 'Segera bayar setelah dikonfirmasi.',
        ];

        $response = $this->post(route('bookings.store', $property->slug), $bookingData);
        $response->assertSessionHasNoErrors();

        // Assert notification was sent to owner
        Notification::assertSentTo(
            $owner,
            NewBookingNotification::class,
            function (NewBookingNotification $notification) use ($property) {
                return $notification->booking->tenant_name === 'Eko Prasetyo' &&
                       $notification->booking->property_id === $property->id;
            }
        );
    }

    /**
     * Test notification content contains correct property type and details.
     */
    public function test_notification_content_has_accurate_property_details(): void
    {
        $owner = User::where('role', 'pemilik')->first();
        $property = Property::where('user_id', $owner->id)->first();

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'start_date' => now()->addDays(1)->toDateString(),
            'duration_months' => 1,
            'end_date' => now()->addMonths(1)->toDateString(),
            'occupants_count' => 1,
            'monthly_price' => $property->price_per_month,
            'total_rent_price' => $property->price_per_month,
            'deposit_price' => 500000,
            'grand_total' => $property->price_per_month + 500000,
            'status' => 'pending',
            'tenant_name' => 'Siti Rahma',
            'tenant_phone' => '081122334455',
            'payment_method' => 'transfer_mandiri',
            'payment_status' => 'unpaid',
        ]);

        $notification = new NewBookingNotification($booking);
        $data = $notification->toArray($owner);

        $this->assertEquals($booking->id, $data['booking_id']);
        $this->assertEquals($booking->booking_code, $data['booking_code']);
        $this->assertEquals($property->title, $data['property_title']);
        $this->assertEquals($property->property_type_label, $data['property_type_label']);
        $this->assertEquals('Siti Rahma', $data['tenant_name']);
        $this->assertStringContainsString($property->property_type_label, $data['message']);
    }

    /**
     * Test owner can view notification center page.
     */
    public function test_owner_can_view_notifications_page(): void
    {
        $owner = User::where('role', 'pemilik')->first();
        $property = Property::where('user_id', $owner->id)->first();

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'start_date' => now()->toDateString(),
            'duration_months' => 1,
            'end_date' => now()->addMonth()->toDateString(),
            'occupants_count' => 1,
            'monthly_price' => 1000000,
            'total_rent_price' => 1000000,
            'deposit_price' => 0,
            'grand_total' => 1000000,
            'status' => 'pending',
            'tenant_name' => 'Dewi Lestari',
            'tenant_phone' => '087788990011',
            'payment_method' => 'tunai',
            'payment_status' => 'unpaid',
        ]);

        // Send actual database notification
        $owner->notify(new NewBookingNotification($booking));

        $this->actingAs($owner);

        $response = $this->get(route('owner.notifications.index'));
        $response->assertStatus(200);
        $response->assertSee('Pusat Notifikasi Booking');
        $response->assertSee('Dewi Lestari');
        $response->assertSee($property->title);
    }

    /**
     * Test owner can mark notification as read.
     */
    public function test_owner_can_mark_notification_as_read(): void
    {
        $owner = User::where('role', 'pemilik')->first();
        $property = Property::where('user_id', $owner->id)->first();

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'start_date' => now()->toDateString(),
            'duration_months' => 2,
            'end_date' => now()->addMonths(2)->toDateString(),
            'occupants_count' => 1,
            'monthly_price' => 1000000,
            'total_rent_price' => 2000000,
            'deposit_price' => 0,
            'grand_total' => 2000000,
            'status' => 'pending',
            'tenant_name' => 'Ahmad Dani',
            'tenant_phone' => '081234567899',
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
        ]);

        $owner->notify(new NewBookingNotification($booking));
        $databaseNotification = $owner->unreadNotifications()->first();
        $this->assertNotNull($databaseNotification);

        $this->actingAs($owner);

        $response = $this->post(route('owner.notifications.read', $databaseNotification->id));
        $response->assertRedirect();

        $this->assertNotNull($databaseNotification->fresh()->read_at);
    }

    /**
     * Test owner can mark all notifications as read.
     */
    public function test_owner_can_mark_all_notifications_as_read(): void
    {
        $owner = User::where('role', 'pemilik')->first();
        $property = Property::where('user_id', $owner->id)->first();

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'start_date' => now()->toDateString(),
            'duration_months' => 1,
            'end_date' => now()->addMonth()->toDateString(),
            'occupants_count' => 1,
            'monthly_price' => 1000000,
            'total_rent_price' => 1000000,
            'deposit_price' => 0,
            'grand_total' => 1000000,
            'status' => 'pending',
            'tenant_name' => 'Rina Nose',
            'tenant_phone' => '081299998888',
            'payment_method' => 'qris',
            'payment_status' => 'unpaid',
        ]);

        $owner->notify(new NewBookingNotification($booking));
        $this->assertGreaterThan(0, $owner->unreadNotifications()->count());

        $this->actingAs($owner);

        $response = $this->post(route('owner.notifications.read-all'));
        $response->assertRedirect();

        $this->assertEquals(0, $owner->unreadNotifications()->count());
    }

    /**
     * Test tenant cannot access owner notifications.
     */
    public function test_tenant_cannot_access_owner_notifications(): void
    {
        $tenant = User::where('role', 'penyewa')->first();

        $this->actingAs($tenant);

        $response = $this->get(route('owner.notifications.index'));
        $response->assertStatus(403);
    }
}
