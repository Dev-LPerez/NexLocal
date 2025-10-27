<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Muestra la página "Mis Reservas" para el turista. (RF-010 - Parte Turista)
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'tourist') {
            abort(403, 'Acceso denegado.');
        }

        $bookings = Booking::where('user_id', $user->id)
                            ->with(['experience', 'experience.user'])
                            ->latest('booking_date')
                            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar la petición
        $validatedData = $request->validate([
            'experience_id' => 'required|exists:experiences,id',
            'booking_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:' . Carbon::now()->format('Y-m-d\TH:i'),
        ], [
            'booking_date.after_or_equal' => 'La fecha y hora de la reserva debe ser futura.',
            'booking_date.date_format' => 'El formato de fecha y hora no es válido.',
        ]);

        // 2. Comprobar que el usuario esté autenticado y sea turista
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para reservar.');
        }
        if ($user->role !== 'tourist') {
            return back()->with('error', 'Solo los turistas pueden realizar reservas.');
        }

        // 3. Obtener la experiencia
        $experience = Experience::findOrFail($validatedData['experience_id']);

        // 4. Crear la reserva
        Booking::create([
            'user_id' => $user->id,
            'experience_id' => $validatedData['experience_id'],
            'booking_date' => $validatedData['booking_date'],
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', '¡Reserva realizada con éxito para "' . $experience->title . '"!');
    }

    /**
     * Cancela una reserva (acción del Turista).
     */
    public function cancel(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $booking->user_id) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        if (!in_array($booking->status, ['confirmed', 'pending'])) {
            return back()->with('error', 'No puedes cancelar una reserva que ya está completada o fue cancelada.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Reserva cancelada correctamente.');
    }

    /**
     * Confirma una reserva (acción del Guía).
     */
    public function confirm(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $booking->experience->user_id) {
            abort(403, 'No tienes permiso para gestionar esta reserva.');
        }

        $booking->status = 'confirmed';
        $booking->save();

        return back()->with('success', 'Reserva confirmada.');
    }

    /**
     * Cancela una reserva (acción del Guía).
     */
    public function guideCancel(Booking $booking)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $booking->experience->user_id) {
            abort(403, 'No tienes permiso para gestionar esta reserva.');
        }

        if (!in_array($booking->status, ['confirmed', 'pending'])) {
            return back()->with('error', 'No puedes cancelar una reserva que ya está completada o fue cancelada.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Reserva cancelada (notificación enviada al turista).');
    }
}
