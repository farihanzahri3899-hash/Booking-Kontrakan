<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant1 = User::where('email', 'budi@gmail.com')->first();
        $tenant2 = User::where('email', 'siti@gmail.com')->first();
        $properties = Property::all();

        if (! $tenant1 || ! $tenant2 || $properties->count() < 3) {
            return;
        }

        $prop1 = $properties[0]; // Gandaria
        $prop2 = $properties[1]; // Griya Pasteur
        $prop3 = $properties[2]; // Cilandak

        // Booking 1: Budi di Gandaria (Active / Lunas)
        $booking1 = Booking::firstOrCreate(
            ['booking_code' => 'BK-2610-ACT01'],
            [
                'user_id' => $tenant1->id,
                'property_id' => $prop1->id,
                'start_date' => Carbon::now()->subMonths(1)->startOfMonth()->toDateString(),
                'duration_months' => 6,
                'end_date' => Carbon::now()->addMonths(5)->startOfMonth()->toDateString(),
                'occupants_count' => 1,
                'monthly_price' => $prop1->price_per_month,
                'total_rent_price' => $prop1->price_per_month * 6,
                'deposit_price' => $prop1->deposit_amount,
                'grand_total' => ($prop1->price_per_month * 6) + $prop1->deposit_amount,
                'status' => 'active',
                'tenant_name' => $tenant1->name,
                'tenant_phone' => $tenant1->phone,
                'notes' => 'Pindahan dari kosan lama, bawa kendaraan motor 1 unit.',
                'payment_method' => 'transfer_bca',
                'payment_status' => 'verified',
                'payment_proof' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80',
                'paid_at' => Carbon::now()->subMonths(1),
            ]
        );

        // Booking 2: Siti di Cilandak (Pending / Menunggu Konfirmasi)
        $booking2 = Booking::firstOrCreate(
            ['booking_code' => 'BK-2610-PND02'],
            [
                'user_id' => $tenant2->id,
                'property_id' => $prop3->id,
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'duration_months' => 3,
                'end_date' => Carbon::now()->addDays(5)->addMonths(3)->toDateString(),
                'occupants_count' => 2,
                'monthly_price' => $prop3->price_per_month,
                'total_rent_price' => $prop3->price_per_month * 3,
                'deposit_price' => $prop3->deposit_amount,
                'grand_total' => ($prop3->price_per_month * 3) + $prop3->deposit_amount,
                'status' => 'pending',
                'tenant_name' => $tenant2->name,
                'tenant_phone' => $tenant2->phone,
                'notes' => 'Tinggal berdua dengan adik perempuan (mahasiswi).',
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'payment_proof' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80',
                'paid_at' => Carbon::now()->subHours(2),
            ]
        );

        // Booking 3: Budi sewa selesai di Griya Pasteur (Completed)
        $booking3 = Booking::firstOrCreate(
            ['booking_code' => 'BK-2605-CMP03'],
            [
                'user_id' => $tenant1->id,
                'property_id' => $prop2->id,
                'start_date' => Carbon::now()->subMonths(7)->toDateString(),
                'duration_months' => 6,
                'end_date' => Carbon::now()->subMonths(1)->toDateString(),
                'occupants_count' => 3,
                'monthly_price' => $prop2->price_per_month,
                'total_rent_price' => $prop2->price_per_month * 6,
                'deposit_price' => $prop2->deposit_amount,
                'grand_total' => ($prop2->price_per_month * 6) + $prop2->deposit_amount,
                'status' => 'completed',
                'tenant_name' => $tenant1->name,
                'tenant_phone' => $tenant1->phone,
                'notes' => 'Sewa dinas proyek Bandung 6 bulan.',
                'payment_method' => 'transfer_mandiri',
                'payment_status' => 'verified',
                'paid_at' => Carbon::now()->subMonths(7),
            ]
        );

        // Review sample
        Review::firstOrCreate(
            ['booking_id' => $booking3->id],
            [
                'user_id' => $tenant1->id,
                'property_id' => $prop2->id,
                'rating' => 5,
                'comment' => 'Sangat nyaman sekali! Pemilik rumah ramah dan responsif saat ada kendala kran air langsung diperbaiki hari itu juga. Lingkungan sangat tenang dan asri.',
            ]
        );

        // Add additional reviews for other properties to show vibrant social proof
        Review::firstOrCreate(
            ['user_id' => $tenant2->id, 'property_id' => $prop1->id],
            [
                'rating' => 5,
                'comment' => 'Lokasi strategis banget di Gandaria. Kamar mandinya bersih, AC dingin, dan aman ada pagar yang selalu dikunci saat malam hari. Recommended!',
            ]
        );

        Review::firstOrCreate(
            ['user_id' => $tenant1->id, 'property_id' => $prop3->id],
            [
                'rating' => 5,
                'comment' => 'Petakannya rapi, cat bersih, dan tidak pengap sama sekali. Sangat pas untuk pekerja kantoran di area Cilandak.',
            ]
        );
    }
}
