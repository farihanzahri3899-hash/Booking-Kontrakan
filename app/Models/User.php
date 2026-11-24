<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'kyc_status',
        'kyc_id_type',
        'kyc_id_number',
        'kyc_document_path',
        'kyc_selfie_path',
        'kyc_rejection_reason',
        'kyc_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kyc_verified_at' => 'datetime',
        ];
    }

    /**
     * Properties owned by this user (if pemilik/admin).
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Bookings made by this tenant.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Reviews submitted by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Wishlist favorites.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Complaints submitted by this user.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Conversations as tenant.
     */
    public function conversationsAsTenant(): HasMany
    {
        return $this->hasMany(Conversation::class, 'tenant_id');
    }

    /**
     * Conversations as owner.
     */
    public function conversationsAsOwner(): HasMany
    {
        return $this->hasMany(Conversation::class, 'owner_id');
    }

    /**
     * Check if user is a system administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a property owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'pemilik';
    }

    /**
     * Check if user is an admin or property owner.
     */
    public function isOwnerOrAdmin(): bool
    {
        return in_array($this->role, ['pemilik', 'admin'], true);
    }

    /**
     * Check if user is a tenant.
     */
    public function isTenant(): bool
    {
        return $this->role === 'penyewa';
    }

    /**
     * Check if user is verified with KYC blue checkmark badge.
     */
    public function isVerified(): bool
    {
        return $this->kyc_status === 'verified';
    }

    public function isKycPending(): bool
    {
        return $this->kyc_status === 'pending';
    }

    public function getKycStatusLabelAttribute(): string
    {
        return match ($this->kyc_status) {
            'verified' => 'Terverifikasi (Centang Biru)',
            'pending' => 'Menunggu Verifikasi Admin',
            'rejected' => 'Verifikasi Ditolak',
            default => 'Belum Terverifikasi',
        };
    }

    public function getKycStatusBadgeClassAttribute(): string
    {
        return match ($this->kyc_status) {
            'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }
}
