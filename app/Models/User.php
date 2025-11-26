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
        'role',
        'identity_document_path',
        'identity_document_back_path',
        'identity_verified_at',
        'verification_status',
        'rejection_reason',
        'is_suspended',
        'suspension_reason',
        'suspended_at',
        'bio',
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

    /**
     * Get all notifications for this user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Check if the guide is verified and can create experiences.
     */
    public function isVerifiedGuide(): bool
    {
        return $this->role === 'guide' &&
               $this->verification_status === 'approved' &&
               $this->identity_verified_at !== null;
    }

    /**
     * Check if the guide has pending verification.
     */
    public function hasPendingVerification(): bool
    {
        return $this->role === 'guide' &&
               $this->verification_status === 'pending';
    }

    /**
     * Check if the guide verification was rejected.
     */
    public function isVerificationRejected(): bool
    {
        return $this->role === 'guide' &&
               $this->verification_status === 'rejected';
    }

    /**
     * Check if the user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->is_suspended === true;
    }

    /**
     * Suspend the user account.
     */
    public function suspend(string $reason): void
    {
        $this->is_suspended = true;
        $this->suspension_reason = $reason;
        $this->suspended_at = now();
        $this->save();
    }

    /**
     * Restore the user account.
     */
    public function restore(): void
    {
        $this->is_suspended = false;
        $this->suspension_reason = null;
        $this->suspended_at = null;
        $this->save();
    }
}
