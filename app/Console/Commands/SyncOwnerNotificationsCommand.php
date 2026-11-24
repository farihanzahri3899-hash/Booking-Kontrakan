<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationSyncService;
use Illuminate\Console\Command;

class SyncOwnerNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:sync-owner {--owner-id= : ID spesifik pemilik untuk disinkronkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hubungkan dan sinkronkan notifikasi booking dan laporan lama ke pemilik kontrakan yang baru';

    /**
     * Execute the console command.
     */
    public function handle(NotificationSyncService $service): int
    {
        $this->info('Memulai sinkronisasi notifikasi ke pemilik kontrakan...');

        $ownerId = $this->option('owner-id');
        $specificOwner = null;

        if ($ownerId) {
            $specificOwner = User::find($ownerId);
            if (! $specificOwner) {
                $this->error("Pemilik dengan ID {$ownerId} tidak ditemukan.");
                return self::FAILURE;
            }
            $this->info("Menyinkronkan khusus untuk pemilik: {$specificOwner->name} ({$specificOwner->email})");
        }

        $result = $service->syncAllNotificationsToOwners($specificOwner);

        $this->newLine();
        $this->table(
            ['Kategori', 'Jumlah'],
            [
                ['Notifikasi Booking Disinkronkan', $result['synced_bookings']],
                ['Notifikasi Laporan Disinkronkan', $result['synced_complaints']],
                ['Notifikasi Lama Dialihkan ke Pemilik Baru', $result['relinked_notifications']],
            ]
        );

        $this->info('Sinkronisasi notifikasi ke pemilik baru berhasil diselesaikan!');

        return self::SUCCESS;
    }
}
