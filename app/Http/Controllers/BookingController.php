<?php
// la ruta del el archivo del codigo: app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AvailabilitySlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Helpers\NotificationHelper;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['experience', 'availabilitySlot', 'review']) // Asegúrate de incluir 'review'
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Cambiado de get() a paginate(10)

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'availability_slot_id' => 'required|exists:availability_slots,id',
            'num_travelers' => 'required|integer|min:1',
            // Omitir validación de 'payment_method_id' por ahora
        ]);

        // Prevenir que un guía solicite una reserva
        $user = Auth::user();
        if ($user && $user->role === 'guide') {
            return back()->with('error', 'Los guías no pueden reservar experiencias.');
        }

        $slot = AvailabilitySlot::with('experience')->findOrFail($request->availability_slot_id);

        // Verificar si hay cupos disponibles
        if ($request->num_travelers > $slot->available_spots) {
            throw ValidationException::withMessages([
                'num_travelers' => 'No hay suficientes cupos disponibles para esta fecha. Cupos restantes: ' . $slot->available_spots,
            ]);
        }

        // Calcular el monto total
        $totalAmount = $slot->experience->price * $request->num_travelers;

        // Lógica de pago (simulada por ahora)
        // En un caso real, aquí se procesaría el pago con Stripe/PayPal
        // y se obtendría un 'payment_intent_id'
        $paymentIntentId = 'pi_' . uniqid(); // ID de pago simulado
        $paymentStatus = 'succeeded'; // Estado de pago simulado

        // Si el pago es exitoso, crear la reserva
        if ($paymentStatus == 'succeeded') {
            // Actualizar los cupos disponibles
            // Se usa DB::transaction en un escenario real para atomicidad
            $slot->decrement('available_spots', $request->num_travelers);

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'experience_id' => $slot->experience_id,
                'availability_slot_id' => $slot->id,
                'num_travelers' => $request->num_travelers,
                'total_amount' => $totalAmount,
                'status' => 'pending', // Inicia como 'pending' según el nuevo flujo
                'payment_intent_id' => $paymentIntentId,
                'payment_status' => $paymentStatus,
                'paid_at' => now(),
            ]);

            // Notificar al guía sobre la nueva reserva
            NotificationHelper::newBookingForGuide($slot->experience->user, $booking);

            return redirect()->route('bookings.index')->with('success', '¡Reserva realizada con éxito! Esperando confirmación del guía.');
        }

        return back()->with('error', 'Hubo un problema al procesar tu pago. Por favor, intenta de nuevo.');
    }

    /**
     * Update the status of a booking (e.g., confirm, cancel, complete).
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:confirmed,cancelled,in_progress,completed',
        ]);

        $newStatus = $request->status;
        $user = Auth::user();
        $isGuide = $user->id === $booking->experience->user_id;
        $isTourist = $user->id === $booking->user_id;

        // --- INICIO DE LA LÓGICA CORREGIDA ---

        // 1. Acción de Cancelar (Permitida para ambos, antes de ser completada)
        if ($newStatus === 'cancelled') {
            if (!$isTourist && !$isGuide) {
                abort(403, 'No tienes permiso para cancelar esta reserva.');
            }
            if ($booking->status === 'completed') {
                return back()->with('error', 'No se puede cancelar una reserva ya completada.');
            }

            // Lógica de Reembolso (pendiente)
            // ...

            // Devolver cupos al slot SOLO si $booking->availabilitySlot existe y $booking->num_travelers es numérico y mayor a 0
            if ($booking->availabilitySlot && is_numeric($booking->num_travelers) && $booking->num_travelers > 0) {
                $booking->availabilitySlot->increment('available_spots', (int) $booking->num_travelers);
            }

            $booking->status = 'cancelled';
            $booking->save();

            // Notificar a ambas partes sobre la cancelación
            if ($isTourist) {
                NotificationHelper::bookingCancelled($booking->experience->user, $booking, 'tourist');
            } elseif ($isGuide) {
                NotificationHelper::bookingCancelled($booking->user, $booking, 'guide');
            }

            return back()->with('success', 'Reserva cancelada.');
        }

        // 2. Acciones del Guía
        if ($isGuide) {
            // Guía confirma la reserva (de pending a confirmed)
            if ($newStatus === 'confirmed' && $booking->status === 'pending') {
                $booking->status = 'confirmed';
                $booking->save();

                // Notificar al turista que su reserva fue confirmada
                NotificationHelper::bookingConfirmed($booking->user, $booking);

                return back()->with('success', 'Reserva confirmada.');
            }

            // Guía inicia la experiencia (de confirmed a in_progress)
            // ¡ESTA ES LA LÍNEA QUE FALTABA Y CAUSABA EL 403!
            if ($newStatus === 'in_progress' && $booking->status === 'confirmed') {
                $booking->status = 'in_progress';
                $booking->save();
                return back()->with('success', 'Experiencia marcada como "En Curso".');
            }
        }

        // 3. Acción de Completar (Ambos, de in_progress a completed)
        if ($newStatus === 'completed' && $booking->status === 'in_progress') {
            if ($isTourist) {
                $booking->tourist_confirmed_completed = true;
            } elseif ($isGuide) {
                $booking->guide_confirmed_completed = true;
            } else {
                // Si no es ni turista ni guía, no debe estar aquí
                abort(403, 'No tienes permiso para esta acción.');
            }

            // Verificar si ambas partes han confirmado
            if ($booking->tourist_confirmed_completed && $booking->guide_confirmed_completed) {
                $booking->status = 'completed';

                // Notificar al turista para que deje una reseña
                NotificationHelper::bookingCompleted($booking->user, $booking);
            }

            $booking->save();
            return back()->with('success', 'Has marcado la experiencia como completada.');
        }

        // --- FIN DE LA LÓGICA CORREGIDA ---

        // Si ninguna regla coincide, es una acción no permitida
        abort(403, 'Acción no permitida o estado incorrecto.');
    }

    /**
     * Marca una reserva como completada (sistema de dos pasos: turista y guía deben confirmar).
     */
    public function markAsCompleted(Request $request, Booking $booking)
    {
        $user = auth()->user();
        $isGuide = $user && $user->id === $booking->experience->user_id;
        $isTourist = $user && $user->id === $booking->user_id;

        if (!$isGuide && !$isTourist) {
            abort(403, 'No tienes permiso para completar esta reserva.');
        }

        // Solo se puede marcar como completada si está en progreso
        if ($booking->status !== 'in_progress') {
            return back()->with('error', 'Solo puedes completar una reserva que está en curso.');
        }

        if ($isTourist) {
            $booking->tourist_confirmed_completed = true;
        }
        if ($isGuide) {
            $booking->guide_confirmed_completed = true;
        }

        // Si ambos han confirmado, se marca como completada
        if ($booking->tourist_confirmed_completed && $booking->guide_confirmed_completed) {
            $booking->status = 'completed';
        }

        $booking->save();
        return back()->with('success', '¡Reserva marcada como completada!');
    }
}
