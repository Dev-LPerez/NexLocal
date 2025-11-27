<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'duration',
        'price',
        'image_path',
        'category',
        'includes',
        'not_includes',
        'meeting_point_name',
        'meeting_point_lat',
        'meeting_point_lng',
        'status',
        'is_featured',
        'moderation_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'includes' => 'array',
            'not_includes' => 'array',
            'price' => 'decimal:2',
            'meeting_point_lat' => 'decimal:7',
            'meeting_point_lng' => 'decimal:7',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the user (guide) that owns the experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener solo experiencias publicadas.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope para obtener solo experiencias destacadas.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para obtener experiencias visibles públicamente (publicadas y destacadas primero).
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('status', 'published')
                     ->orderByDesc('is_featured')
                     ->latest();
    }

    /**
     * Get the availability slots for the experience.
     */
    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class)->orderBy('start_time');
    }

    /**
     * Get the bookings for the experience.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the experience.
     */
    public function reviews(): HasMany
    {
        // Ordenar para mostrar las más nuevas primero
        return $this->hasMany(Review::class)->latest();
    }
}
