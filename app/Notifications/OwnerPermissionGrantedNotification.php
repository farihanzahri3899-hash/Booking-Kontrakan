<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerPermissionGrantedNotification extends Notification
{
    use Queueable;

    public function __construct(public ?string $customMessage = null) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'owner_permission',
            'title' => 'Izin Pemilik Kontrakan Disetujui',
            'message' => $this->customMessage ?? 'Selamat! Akun Anda telah disetujui sebagai Pemilik Kontrakan oleh Administrator. Anda sekarang dapat mendaftarkan dan mengelola unit kontrakan serta menerima pesanan penyewa.',
            'action_url' => route('owner.dashboard'),
        ];
    }
}
