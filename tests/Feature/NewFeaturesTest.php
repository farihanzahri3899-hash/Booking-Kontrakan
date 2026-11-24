<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use DatabaseTransactions;

    protected User $tenant;

    protected User $owner;

    protected Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = User::firstOrCreate(
            ['email' => 'penyewa_test@example.com'],
            [
                'name' => 'Budi Penyewa Test',
                'password' => bcrypt('password'),
                'role' => 'penyewa',
                'phone' => '081234567890',
            ]
        );

        $this->owner = User::firstOrCreate(
            ['email' => 'pemilik_test@example.com'],
            [
                'name' => 'Pak Haji Test',
                'password' => bcrypt('password'),
                'role' => 'pemilik',
                'phone' => '081234567891',
            ]
        );

        $this->property = Property::firstOrCreate(
            ['slug' => 'kontrakan-asri-test'],
            [
                'user_id' => $this->owner->id,
                'title' => 'Kontrakan Asri Test',
                'description' => 'Kontrakan bersih dan nyaman dekat kampus UNJA Mendalo.',
                'main_image' => 'properties/sample.jpg',
                'property_type' => 'rumah',
                'price_per_month' => 1500000,
                'price_per_year' => 16000000,
                'deposit_amount' => 500000,
                'city' => 'Kota Jambi',
                'district' => 'Telanaipura',
                'address' => 'Jl. Arif Rahman Hakim No. 12',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'building_size' => 45,
                'land_size' => 60,
                'electricity_type' => 'token',
                'water_source' => 'pdam',
                'is_available' => true,
                'is_featured' => true,
            ]
        );
    }

    /**
     * FITUR 1: Laporan penyewa (contoh: AC rusak boss) & Tanggapan Pemilik
     */
    public function test_tenant_can_submit_complaint_and_owner_can_respond(): void
    {
        // Buat booking terkonfirmasi untuk penyewa
        $booking = Booking::create([
            'booking_code' => 'BK-'.strtoupper(uniqid()),
            'user_id' => $this->tenant->id,
            'property_id' => $this->property->id,
            'start_date' => now()->toDateString(),
            'duration_months' => 6,
            'end_date' => now()->addMonths(6)->toDateString(),
            'monthly_price' => 1500000,
            'total_rent_price' => 9000000,
            'deposit_price' => 500000,
            'grand_total' => 9500000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'transfer_bca',
            'tenant_name' => $this->tenant->name,
            'tenant_phone' => $this->tenant->phone,
        ]);

        // 1. Tenant membuka halaman form laporan
        $response = $this->actingAs($this->tenant)->get(route('complaints.create'));
        $response->assertStatus(200);
        $response->assertSee('Laporkan Kendala atau Kerusakan Fasilitas');

        // 2. Tenant mengirim laporan masalah
        $postData = [
            'booking_id' => $booking->id,
            'property_id' => $this->property->id,
            'title' => 'AC Rusak Boss',
            'category' => 'fasilitas',
            'description' => 'AC di kamar utama tidak dingin dan mengeluarkan bunyi mendesis.',
        ];

        $postResponse = $this->actingAs($this->tenant)->post(route('complaints.store'), $postData);
        $postResponse->assertRedirect();

        $complaint = Complaint::where('title', 'AC Rusak Boss')->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('pending', $complaint->status);
        $this->assertEquals($this->tenant->id, $complaint->user_id);
        $this->assertEquals($this->property->id, $complaint->property_id);

        // 3. Pemilik dapat melihat laporan di panel keluhan pemilik
        $ownerListResponse = $this->actingAs($this->owner)->get(route('owner.complaints.index'));
        $ownerListResponse->assertStatus(200);
        $ownerListResponse->assertSee('AC Rusak Boss');

        // 4. Pemilik merespons laporan dan mengubah status menjadi in_progress
        $respondResponse = $this->actingAs($this->owner)->post(
            route('owner.complaints.respond', $complaint),
            [
                'status' => 'in_progress',
                'owner_response' => 'Siap mas, teknisi AC sudah dijadwalkan datang besok jam 10 pagi.',
            ]
        );
        $respondResponse->assertRedirect();

        $complaint->refresh();
        $this->assertEquals('in_progress', $complaint->status);
        $this->assertStringContainsString('teknisi AC sudah dijadwalkan', $complaint->owner_response);

        // 4b. Pemilik menyelesaikan perbaikan (status: resolved)
        $this->actingAs($this->owner)->post(
            route('owner.complaints.respond', $complaint),
            [
                'status' => 'resolved',
                'owner_response' => 'AC sudah diperbaiki dan freon telah diisi ulang.',
            ]
        );
        $complaint->refresh();
        $this->assertEquals('resolved', $complaint->status);
        $this->assertNotNull($complaint->resolved_at);

        // 5. Penyewa melihat tanggapan pemilik
        $tenantShowResponse = $this->actingAs($this->tenant)->get(route('complaints.show', $complaint));
        $tenantShowResponse->assertStatus(200);
        $tenantShowResponse->assertSee('Selesai Diperbaiki');
        $tenantShowResponse->assertSee('AC sudah diperbaiki dan freon telah diisi ulang');
    }

    /**
     * FITUR 2: Balas ulasan penyewa sebagai pemilik
     */
    public function test_owner_can_reply_to_tenant_review(): void
    {
        // 1. Buat ulasan oleh penyewa
        $review = Review::create([
            'user_id' => $this->tenant->id,
            'property_id' => $this->property->id,
            'rating' => 5,
            'comment' => 'Lingkungan sangat tenang, air PDAM lancar 24 jam.',
        ]);

        // 2. Pemilik memberikan balasan resmi
        $replyResponse = $this->actingAs($this->owner)->post(
            route('owner.reviews.reply', $review),
            [
                'owner_reply' => 'Terima kasih Mas Budi atas ulasannya! Semoga betah tinggal di kontrakan kami.',
            ]
        );

        $replyResponse->assertRedirect();

        $review->refresh();
        $this->assertTrue($review->hasOwnerReply());
        $this->assertEquals('Terima kasih Mas Budi atas ulasannya! Semoga betah tinggal di kontrakan kami.', $review->owner_reply);
        $this->assertNotNull($review->owner_replied_at);

        // 3. Tampil di halaman detail kontrakan
        $showResponse = $this->get(route('properties.show', $this->property->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Tanggapan dari Pemilik Kontrakan');
        $showResponse->assertSee('Terima kasih Mas Budi');
    }

    /**
     * FITUR 3: Landing page sebagai penyewa & fitur pilih kota
     */
    public function test_landing_page_provides_tenant_overview_and_full_city_list(): void
    {
        // 1. Guest akses landing page
        $guestResponse = $this->get(route('home'));
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Kota Jambi');
        $guestResponse->assertSee('Mendalo (UNJA)');
        $guestResponse->assertSee('Telanaipura');
        $guestResponse->assertSee('Pilih Kota');

        // 2. Penyewa terautentikasi mendapatkan Personalized Tenant Overview
        Favorite::firstOrCreate([
            'user_id' => $this->tenant->id,
            'property_id' => $this->property->id,
        ]);

        $authResponse = $this->actingAs($this->tenant)->get(route('home'));
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Halo, '.$this->tenant->name);
        $authResponse->assertSee('Wishlist (1)');
        $authResponse->assertSee('Komunikasi Lancar, Kenyamanan Maksimal');

        // 3. Filter kota di halaman jelajah kontrakan
        $cityFilterResponse = $this->get(route('properties.index', ['city' => 'Kota Jambi']));
        $cityFilterResponse->assertStatus(200);
        $cityFilterResponse->assertSee($this->property->title);
    }

    /**
     * FITUR 4: Komunikasi & real-time: Chat Langsung Penyewa & Pemilik (In-App Messaging)
     */
    public function test_tenant_and_owner_can_chat_in_app(): void
    {
        // 1. Penyewa memulai percakapan dari halaman kontrakan
        $startChatResponse = $this->actingAs($this->tenant)->post(route('chat.start', $this->property));
        $startChatResponse->assertRedirect();

        $conversation = Conversation::where('property_id', $this->property->id)
            ->where('tenant_id', $this->tenant->id)
            ->where('owner_id', $this->owner->id)
            ->first();

        $this->assertNotNull($conversation);

        // 2. Penyewa mengirim pesan pertama mengenai aturan kontrakan
        $sendMsgResponse = $this->actingAs($this->tenant)->post(
            route('chat.send', $conversation),
            [
                'message' => 'Halo Pak Haji, apakah ada jam malam untuk tamu di kontrakan ini?',
            ]
        );
        $sendMsgResponse->assertRedirect();

        $message = Message::where('conversation_id', $conversation->id)->first();
        $this->assertNotNull($message);
        $this->assertEquals('Halo Pak Haji, apakah ada jam malam untuk tamu di kontrakan ini?', $message->message);
        $this->assertEquals($this->tenant->id, $message->sender_id);

        // 3. Pemilik membuka halaman chat dan membalas pesan
        $ownerChatPage = $this->actingAs($this->owner)->get(route('chat.show', $conversation));
        $ownerChatPage->assertStatus(200);
        $ownerChatPage->assertSee('Halo Pak Haji, apakah ada jam malam');

        $ownerReplyMsg = $this->actingAs($this->owner)->post(
            route('chat.send', $conversation),
            [
                'message' => 'Halo Mas Budi! Tidak ada jam malam ketat, yang penting saling menjaga ketertiban lingkungan.',
            ]
        );
        $ownerReplyMsg->assertRedirect();

        $this->assertEquals(2, $conversation->messages()->count());

        // 4. API endpoint real-time polling mengembalikan pesan dalam format JSON
        $jsonResponse = $this->actingAs($this->tenant)->getJson(route('chat.messages', $conversation));
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJsonStructure([
            'success',
            'messages' => [
                '*' => ['id', 'text', 'sender_id', 'sender_name', 'is_me', 'time', 'date'],
            ],
        ]);
        $jsonResponse->assertSee('Tidak ada jam malam ketat');
    }
}
