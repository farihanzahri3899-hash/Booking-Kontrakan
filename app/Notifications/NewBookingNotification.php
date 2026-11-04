<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Booking $booking) {}

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
        $property = $this->booking->property;
        $typeLabel = $property?->property_type_label ?? 'Kontrakan';

        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'property_id' => $property?->id,
            'property_title' => $property?->title ?? 'Kontrakan',
            'property_type' => $property?->property_type ?? 'rumah',
            'property_type_label' => $typeLabel,
            'property_slug' => $property?->slug,
            'property_image' => $property?->main_image,
            'tenant_name' => $this->booking->tenant_name,
            'tenant_phone' => $this->booking->tenant_phone,
            'duration_months' => $this->booking->duration_months,
            'grand_total' => $this->booking->grand_total,
            'formatted_grand_total' => $this->booking->formatted_grand_total,
            'start_date' => $this->booking->start_date?->format('d M Y'),
            'message' => "Ada pesanan booking baru untuk {$typeLabel}: {$property?->title} dari {$this->booking->tenant_name}.",
            'action_url' => route('owner.bookings.index', ['q' => $this->booking->booking_code]),
        ];
    }
}
