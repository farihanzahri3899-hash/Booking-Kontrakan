<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComplaintNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Complaint $complaint) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification stored in database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $property = $this->complaint->property;
        $typeLabel = $property?->property_type_label ?? 'Kontrakan';
        $user = $this->complaint->user;

        return [
            'type' => 'complaint',
            'complaint_id' => $this->complaint->id,
            'complaint_title' => $this->complaint->title,
            'category' => $this->complaint->category,
            'category_label' => $this->complaint->category_label,
            'property_id' => $property?->id,
            'property_title' => $property?->title ?? 'Kontrakan',
            'property_type' => $property?->property_type ?? 'rumah',
            'property_type_label' => $typeLabel,
            'property_slug' => $property?->slug,
            'property_image' => $property?->main_image,
            'tenant_name' => $user?->name ?? 'Penyewa',
            'tenant_phone' => $user?->phone ?? '-',
            'message' => "Laporan kendala baru: {$this->complaint->title} pada {$property?->title} ({$this->complaint->category_label}).",
            'action_url' => route('owner.complaints.show', $this->complaint),
        ];
    }
}
