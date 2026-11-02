<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'property_id',
        'start_date',
        'duration_months',
        'end_date',
        'occupants_count',
        'monthly_price',
        'total_rent_price',
        'deposit_price',
        'grand_total',
        'status',
        'tenant_name',
        'tenant_phone',
        'tenant_id_card',
        'notes',
        'rejection_reason',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'paid_at' => 'datetime',
            'duration_months' => 'integer',
            'occupants_count' => 'integer',
            'monthly_price' => 'integer',
            'total_rent_price' => 'integer',
            'deposit_price' => 'integer',
            'grand_total' => 'integer',
        ];
    }

    /**
     * Tenant who booked.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booked property.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Review left for this booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Generate unique booking code.
     */
    public static function generateCode(): string
    {
        do {
            $code = 'BK-'.date('ym').'-'.strtoupper(Str::random(5));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Indonesian status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Sedang Menghuni',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    /**
     * Status badge styling classes.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
            'cancelled' => 'bg-gray-100 text-gray-700 border-gray-200',
            'completed' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Indonesian payment status label.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'paid' => 'Menunggu Verifikasi',
            'verified' => 'Lunas',
            default => ucfirst($this->payment_status),
        };
    }

    /**
     * Payment badge styling classes.
     */
    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200',
            'paid' => 'bg-amber-50 text-amber-700 border-amber-200',
            'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    /**
     * Formatted grand total.
     */
    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp '.number_format($this->grand_total, 0, ',', '.');
    }
}
