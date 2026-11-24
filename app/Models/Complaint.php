<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'booking_id',
        'title',
        'category',
        'description',
        'photo',
        'status',
        'owner_response',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'fasilitas' => 'Fasilitas & Furnitur',
            'listrik_air' => 'Listrik & Air',
            'kebersihan' => 'Kebersihan',
            'keamanan' => 'Keamanan & Lingkungan',
            default => 'Lainnya',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Respon',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Menunggu Respon',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200',
            'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }
}
