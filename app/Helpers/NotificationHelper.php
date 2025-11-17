<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Crear una notificación de reserva confirmada
     */
    public static function bookingConfirmed(User $user, $booking)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'booking_confirmed',
            'title' => '¡Reserva Confirmada!',
            'message' => "Tu reserva para '{$booking->experience->title}' ha sido confirmada.",
            'icon' => '✅',
            'link' => route('bookings.index'),
        ]);
    }

    /**
     * Crear una notificación de nueva reserva para el guía
     */
    public static function newBookingForGuide(User $guide, $booking)
    {
        return Notification::create([
            'user_id' => $guide->id,
            'type' => 'new_booking',
            'title' => 'Nueva Reserva',
            'message' => "Tienes una nueva reserva para '{$booking->experience->title}' de {$booking->user->name}.",
            'icon' => '📅',
            'link' => route('dashboard'),
        ]);
    }

    /**
     * Crear una notificación de reserva cancelada
     */
    public static function bookingCancelled(User $user, $booking, $cancelledBy = 'tourist')
    {
        $message = $cancelledBy === 'guide'
            ? "El guía ha cancelado tu reserva para '{$booking->experience->title}'."
            : "Tu reserva para '{$booking->experience->title}' ha sido cancelada.";

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'booking_cancelled',
            'title' => 'Reserva Cancelada',
            'message' => $message,
            'icon' => '❌',
            'link' => route('bookings.index'),
        ]);
    }

    /**
     * Crear una notificación de reserva completada
     */
    public static function bookingCompleted(User $user, $booking)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'booking_completed',
            'title' => 'Experiencia Completada',
            'message' => "La experiencia '{$booking->experience->title}' ha sido completada. ¡Déjanos tu reseña!",
            'icon' => '🎉',
            'link' => route('reviews.create') . '?booking_id=' . $booking->id,
        ]);
    }

    /**
     * Crear una notificación de nueva reseña para el guía
     */
    public static function newReview(User $guide, $review)
    {
        $rating = str_repeat('⭐', $review->rating);

        return Notification::create([
            'user_id' => $guide->id,
            'type' => 'new_review',
            'title' => 'Nueva Reseña',
            'message' => "{$review->user->name} dejó una reseña {$rating} en '{$review->experience->title}'.",
            'icon' => '⭐',
            'link' => route('experiences.show', $review->experience_id),
        ]);
    }

    /**
     * Crear una notificación de pago recibido
     */
    public static function paymentReceived(User $guide, $booking)
    {
        return Notification::create([
            'user_id' => $guide->id,
            'type' => 'payment_received',
            'title' => 'Pago Recibido',
            'message' => "Has recibido el pago por la reserva de '{$booking->experience->title}'.",
            'icon' => '💰',
            'link' => route('dashboard'),
        ]);
    }

    /**
     * Crear una notificación de recordatorio de experiencia
     */
    public static function experienceReminder(User $user, $booking)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'experience_reminder',
            'title' => 'Recordatorio de Experiencia',
            'message' => "Tu experiencia '{$booking->experience->title}' es mañana a las {$booking->availabilitySlot->start_time}.",
            'icon' => '🔔',
            'link' => route('bookings.index'),
        ]);
    }

    /**
     * Crear una notificación personalizada
     */
    public static function custom(User $user, string $type, string $title, string $message, ?string $icon = null, ?string $link = null)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon ?? '📢',
            'link' => $link,
        ]);
    }
}

