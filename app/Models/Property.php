<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'property_type',
        'description',
        'price_per_month',
        'price_per_year',
        'deposit_amount',
        'address',
        'city',
        'district',
        'postal_code',
        'map_url',
        'bedrooms',
        'bathrooms',
        'building_size',
        'land_size',
        'electricity_type',
        'water_source',
        'facilities',
        'rules',
        'main_image',
        'images',
        'is_available',
        'is_featured',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'rules' => 'array',
            'images' => 'array',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'price_per_month' => 'integer',
            'price_per_year' => 'integer',
            'deposit_amount' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'building_size' => 'integer',
            'land_size' => 'integer',
        ];
    }

    /**
     * Owner of this property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bookings for this property.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Reviews for this property.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Average rating accessor.
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');

        return $avg ? round($avg, 1) : 5.0;
    }

    /**
     * Total review count accessor.
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Formatted price per month.
     */
    public function getFormattedPriceMonthAttribute(): string
    {
        return 'Rp '.number_format($this->price_per_month, 0, ',', '.');
    }

    /**
     * Formatted price per year.
     */
    public function getFormattedPriceYearAttribute(): ?string
    {
        if (! $this->price_per_year) {
            return null;
        }

        return 'Rp '.number_format($this->price_per_year, 0, ',', '.');
    }

    /**
     * Human readable property type label.
     */
    public function getPropertyTypeLabelAttribute(): string
    {
        return match ($this->property_type) {
            'rumah' => 'Rumah Kontrakan',
            'petakan' => 'Kontrakan Petakan',
            'paviliun' => 'Paviliun Mandiri',
            'kost' => 'Kost Eksklusif',
            default => ucfirst($this->property_type),
        };
    }

    /**
     * Scope for available properties.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for featured properties.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for filtering properties in search.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['q'])) {
            $keyword = trim($filters['q']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('city', 'like', "%{$keyword}%")
                    ->orWhere('district', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        if (! empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (! empty($filters['type'])) {
            $query->where('property_type', $filters['type']);
        }

        if (! empty($filters['min_price'])) {
            $query->where('price_per_month', '>=', (int) $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price_per_month', '<=', (int) $filters['max_price']);
        }

        if (! empty($filters['bedrooms'])) {
            $query->where('bedrooms', '>=', (int) $filters['bedrooms']);
        }

        if (! empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_low' => $query->orderBy('price_per_month', 'asc'),
                'price_high' => $query->orderBy('price_per_month', 'desc'),
                'oldest' => $query->orderBy('created_at', 'asc'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        return $query;
    }
}
