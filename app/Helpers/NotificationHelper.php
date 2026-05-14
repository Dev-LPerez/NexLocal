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

    /**
     * Notificación de Nuevo Pedido para el Negocio
     */
    public static function newOrderForBusiness(User $owner, $order)
    {
        return Notification::create([
            'user_id' => $owner->id,
            'type' => 'new_order',
            'title' => 'Nuevo Pedido',
            'message' => "Tienes un nuevo pedido de {$order->user->name} por $" . number_format($order->total_amount, 2) . ".",
            'icon' => '🛍️',
            'link' => route('dashboard'),
        ]);
    }

    /**
     * Notificación de Actualización de Estado de Pedido para el Cliente
     */
    public static function orderStatusUpdated(User $user, $order)
    {
        $statusText = match ($order->status) {
            'preparing' => 'en preparación',
            'ready' => 'listo para retiro/entrega',
            'delivered' => 'entregado',
            'cancelled' => 'cancelado',
            default => 'actualizado'
        };

        $icon = match ($order->status) {
            'preparing' => '👨‍🍳',
            'ready' => '🛵',
            'delivered' => '✅',
            'cancelled' => '❌',
            default => '🔄'
        };

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'order_status_updated',
            'title' => 'Actualización de Pedido',
            'message' => "Tu pedido está {$statusText}.",
            'icon' => $icon,
            'link' => route('orders.index'),
        ]);
    }

    /**
     * Notificación de Nueva Reseña para Negocios
     */
    public static function newBusinessReview(User $owner, $review)
    {
        $rating = str_repeat('⭐', $review->rating);

        return Notification::create([
            'user_id' => $owner->id,
            'type' => 'new_business_review',
            'title' => 'Nueva Reseña',
            'message' => "{$review->user->name} dejó una reseña {$rating} en tu negocio '{$review->localBusiness->name}'.",
            'icon' => '⭐',
            'link' => route('businesses.show', $review->local_business_id),
        ]);
    }

    /**
     * Crear una notificación genérica (método alternativo más simple)
     *
     * @param int $userId ID del usuario que recibirá la notificación
     * @param string $title Título de la notificación
     * @param string $message Mensaje de la notificación
     * @param string $type Tipo de notificación
     * @param string|null $link URL de destino (opcional)
     * @param string|null $icon Icono/emoji (opcional)
     * @return \App\Models\Notification
     */
    public static function create(int $userId, string $title, string $message, string $type = 'general', ?string $link = null, ?string $icon = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon ?? '📢',
            'link' => $link,
        ]);
    }
}

