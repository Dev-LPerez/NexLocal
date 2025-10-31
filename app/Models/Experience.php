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
        'image_path', // Si tienes imagen
        'category',
        'includes',
        'not_includes',

        // --- CAMPOS AÑADIDOS ---
        'meeting_point_name',
        'meeting_point_lat',
        'meeting_point_lng',
        // --- FIN DE CAMPOS AÑADIDOS ---
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

            // --- CASTS AÑADIDOS ---
            'meeting_point_lat' => 'decimal:7',
            'meeting_point_lng' => 'decimal:7',
            // --- FIN DE CASTS AÑADIDOS ---
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
