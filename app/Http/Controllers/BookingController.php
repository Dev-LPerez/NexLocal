<?php // Filepath: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Experience;
use App\Models\AvailabilitySlot; // <-- Importante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Importante
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Muestra la página "Mis Reservas" para el turista.
     */
    public function index()
    {
        // --- MODIFICACIÓN: Cargar relación 'review' ---
        // Añadimos 'review' para saber si ya se dejó una reseña
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['experience', 'availabilitySlot', 'review']) // <-- 'review' AÑADIDO
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Almacena una nueva reserva.
     */
    public function store(Request $request)
    {
        // --- CORRECCIÓN 2: Validación ---
        // Cambiamos la validación de 'booking_date' a 'availability_slot_id'
        $validatedData = $request->validate([
            'availability_slot_id' => 'required|exists:availability_slots,id',
        ]);

        $slotId = $validatedData['availability_slot_id'];
        $userId = Auth::id();

        try {
            // Usamos una transacción para asegurar que la reserva de cupos sea atómica
            $booking = DB::transaction(function () use ($slotId, $userId) {

                // Buscamos el slot y lo bloqueamos para evitar que dos usuarios reserven el último cupo
                $slot = AvailabilitySlot::where('id', $slotId)
                    ->lockForUpdate() // Previene 'race conditions'
                    ->first();

                if (!$slot) {
                    throw new \Exception('El horario seleccionado ya no existe.');
                }

                $experience = $slot->experience;
                if (!$experience) {
                    throw new \Exception('La experiencia asociada a este horario no fue encontrada.');
                }

                // Verificar si el usuario ya reservó este slot
                $alreadyBooked = Booking::where('user_id', $userId)
                    ->where('availability_slot_id', $slotId)
                    // --- MODIFICACIÓN: Permitir reservar si está 'cancelled' ---
                    ->whereIn('status', ['pending', 'confirmed', 'completed']) // No contar 'cancelled'
                    ->exists();

                if ($alreadyBooked) {
                    throw new \Exception('Ya tienes una reserva activa o completada para este horario.');
                }

                // --- CORRECCIÓN 3: Lógica de Cupos ---
                // Verificamos la columna 'available_spots' que es nuestro contador real
                if ($slot->available_spots <= 0) {
                    throw new \Exception('Lo sentimos, este horario ya está agotado.');
                }

                // Descontamos un cupo disponible
                $slot->decrement('available_spots');

                // Crear la reserva
                $booking = Booking::create([
                    'user_id' => $userId,
                    'experience_id' => $experience->id,
                    'availability_slot_id' => $slot->id,
                    'status' => 'confirmed', // Confirmada de inmediato (sin pago)
                    'total_amount' => $experience->price,
                    'payment_id' => null,
                    'booking_date' => $slot->start_time, // <-- AÑADIDO: Guardar la fecha del evento
                ]);

                return $booking;
            });

            // Redirigir a "Mis Reservas"
            return redirect()->route('bookings.index')->with('success', '¡Reserva confirmada con éxito!');

        } catch (\Exception $e) {
            // Si algo falla (sin cupos, etc.), volvemos con el mensaje de error
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Actualiza el estado de una reserva (Cancelar/Confirmar por Guía).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // MODIFICACIÓN: Agregar 'in_progress' a los estados permitidos
        $request->validate(['status' => 'required|in:confirmed,cancelled,in_progress']);
        $newStatus = $request->input('status');
        $oldStatus = $booking->status; // Guardamos el estado anterior

        $isGuide = Auth::id() === $booking->experience->user_id;
        $isTourist = Auth::id() === $booking->user_id;

        // El turista solo puede cancelar
        if ($isTourist && $newStatus === 'cancelled') {

            // Permitir solo si la reserva está activa
            if (!in_array($oldStatus, ['pending', 'confirmed'])) {

                return redirect()->back()->with('error', 'No puedes cancelar una reserva que no está activa.');
            }

            // Y si la fecha del evento aún no ha pasado
            if ($booking->availabilitySlot && $booking->availabilitySlot->start_time <= now()) {
                return redirect()->back()->with('error', 'No puedes cancelar una reserva que ya ha comenzado o pasado.');
            }
        }
        // El guía puede confirmar o cancelar
        else if ($isGuide && in_array($newStatus, ['confirmed', 'cancelled'])) {
            // Lógica de guía
        }
        // Si no es ninguno de los casos
        else {
            abort(403, 'No tienes permiso para modificar esta reserva.');
        }

        // --- CORRECCIÓN 4: Devolver Cupo ---
        // Si la reserva se cancela (y no estaba ya cancelada), devolvemos el cupo
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            DB::transaction(function () use ($booking, $newStatus) {
                $booking->update(['status' => $newStatus]);

                // Devolvemos el cupo al slot correspondiente
                $slot = $booking->availabilitySlot;
                // Solo devolver cupos si el slot existe Y es una fecha futura
                if ($slot && $slot->start_time > now()) {
                    $slot->increment('available_spots');
                }
            });
            return redirect()->back()->with('success', 'Reserva cancelada.');
        }

        // Si es el guía confirmando
        if ($isGuide && $newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
            $booking->update(['status' => $newStatus]);
            return redirect()->back()->with('success', 'Reserva confirmada.');
        }

        return redirect()->back()->with('error', 'No se pudo realizar la acción o no hubo cambios.');
    }

    /**
     * Permite al guía cancelar una reserva (cambio de estado a 'cancelled' y devolución de cupo).
     */
    public function guideCancel(Request $request, Booking $booking)
    {
        // Solo el guía dueño de la experiencia puede cancelar
        if (Auth::id() !== $booking->experience->user_id) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('warning', 'La reserva ya estaba cancelada.');
        }

        // No permitir cancelar reservas completadas
        if ($booking->status === 'completed') {
            return redirect()->back()->with('error', 'No se puede cancelar una reserva ya completada.');
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
            // Devolver cupo al slot
            $slot = $booking->availabilitySlot;
            if ($slot && $slot->start_time > now()) { // Solo devolver si es futuro
                $slot->increment('available_spots');
            }
        });
        return redirect()->back()->with('success', 'Reserva cancelada correctamente por el guía.');
    }

    /**
     * Marca una reserva como completada (sistema de finalización de dos pasos).
     */
    public function markAsCompleted(Request $request, Booking $booking)
    {
        // Verificar permisos (guía o turista)
        $isGuide = Auth::id() === $booking->experience->user_id;
        $isTourist = Auth::id() === $booking->user_id;

        if (!$isGuide && !$isTourist) {
            abort(403, 'No tienes permiso para modificar esta reserva.');
        }

        // Solo se pueden marcar como completadas las experiencias en progreso
        if ($booking->status !== 'in_progress') {
            return redirect()->back()->with('error', 'Solo se pueden marcar como completadas las experiencias en progreso.');
        }

        // Actualizar la confirmación según quien sea
        if ($isGuide) {
            $booking->guide_confirmed_completed = true;
            $message = 'Has confirmado la finalización. Esperando confirmación del turista.';
        } elseif ($isTourist) {
            $booking->tourist_confirmed_completed = true;
            $message = 'Has confirmado la finalización. Esperando confirmación del guía.';
        }

        // Si ambos confirmaron, cambiar estado a completed
        if ($booking->guide_confirmed_completed && $booking->tourist_confirmed_completed) {
            $booking->status = 'completed';
            $message = 'Experiencia marcada como completada exitosamente.';
        }

        $booking->save();

        return redirect()->back()->with('success', $message);
    }
}
