<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Importar las relaciones necesarias
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Asegúrate de que 'role' esté aquí
        'identity_document_path',
        'identity_verified_at',
        'bio', // corregido a 'bio' para coincidir con la base de datos
        'profile_photo_path',
        'age',
        'hobbies',
        'occupation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'identity_verified_at' => 'datetime', // Añadir casteo
            'password' => 'hashed',
        ];
    }

    /**
     * Get the experiences created by the user (guide).
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get the bookings made by the user (tourist).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all bookings for experiences owned by this user (guide).
     * (Obtiene las reservas a través de las experiencias)
     */
    public function guideBookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Experience::class);
    }

    /**
     * Get the reviews written by the user (tourist).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
