<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Property;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Notifications\NewComplaintNotification;
use Illuminate\Support\Facades\DB;

class NotificationSyncService
{
    /**
     * Hubungkan dan sinkronkan notifikasi pesanan booking dan laporan lama ke pemilik properti yang baru.
     *
     * @param User|null $specificOwner Jika ditentukan, hanya sinkronkan untuk pemilik ini.
     * @return array Ringkasan jumlah notifikasi yang disinkronkan.
     */
    public function syncAllNotificationsToOwners(?User $specificOwner = null): array
    {
        $syncedBookings = 0;
        $syncedComplaints = 0;
        $relinkedNotifications = 0;

        // 1. Dapatkan daftar pemilik atau pemilik tertentu
        $ownersQuery = User::where('role', 'pemilik');
        if ($specificOwner) {
            $ownersQuery->where('id', $specificOwner->id);
        }
        $owners = $ownersQuery->with('properties')->get();

        foreach ($owners as $owner) {
            $ownerPropertyIds = $owner->properties->pluck('id');

            if ($ownerPropertyIds->isEmpty()) {
                continue;
            }

            // 2. Hubungkan notifikasi booking lama dari properti milik owner ini
            $bookings = Booking::whereIn('property_id', $ownerPropertyIds)
                ->with(['property', 'user'])
                ->get();

            foreach ($bookings as $booking) {
                // Periksa apakah notifikasi booking ini sudah ada di database untuk pemilik ini
                $exists = DB::table('notifications')
                    ->where('notifiable_type', User::class)
                    ->where('notifiable_id', $owner->id)
                    ->where('data->booking_id', $booking->id)
                    ->exists();

                if (! $exists) {
                    // Cari apakah ada notifikasi lama untuk booking_id ini yang masih terhubung ke user id lain
                    $oldNotification = DB::table('notifications')
                        ->where('data->booking_id', $booking->id)
                        ->first();

                    if ($oldNotification) {
                        // Alihkan dan hubungkan notifikasi lama ke pemilik baru
                        DB::table('notifications')
                            ->where('id', $oldNotification->id)
                            ->update([
                                'notifiable_id' => $owner->id,
                                'notifiable_type' => User::class,
                                'updated_at' => now(),
                            ]);
                        $relinkedNotifications++;
                    } else {
                        // Buat notifikasi baru untuk pemilik ini
                        $owner->notify(new NewBookingNotification($booking));
                        $syncedBookings++;
                    }
                }
            }

            // 3. Hubungkan notifikasi komplain / kendala fasilitas lama
            $complaints = Complaint::whereIn('property_id', $ownerPropertyIds)
                ->with(['property', 'user'])
                ->get();

            foreach ($complaints as $complaint) {
                $exists = DB::table('notifications')
                    ->where('notifiable_type', User::class)
                    ->where('notifiable_id', $owner->id)
                    ->where('data->complaint_id', $complaint->id)
                    ->exists();

                if (! $exists) {
                    $oldComplaintNotif = DB::table('notifications')
                        ->where('data->complaint_id', $complaint->id)
                        ->first();

                    if ($oldComplaintNotif) {
                        DB::table('notifications')
                            ->where('id', $oldComplaintNotif->id)
                            ->update([
                                'notifiable_id' => $owner->id,
                                'notifiable_type' => User::class,
                                'updated_at' => now(),
                            ]);
                        $relinkedNotifications++;
                    } else {
                        $owner->notify(new NewComplaintNotification($complaint));
                        $syncedComplaints++;
                    }
                }
            }
        }

        return [
            'synced_bookings' => $syncedBookings,
            'synced_complaints' => $syncedComplaints,
            'relinked_notifications' => $relinkedNotifications,
        ];
    }
}
