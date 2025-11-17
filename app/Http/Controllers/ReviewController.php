<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class ReviewController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva reseña.
     */
    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');
        if (!$bookingId) {
            abort(404, 'Reserva no especificada.');
        }

        $booking = Booking::with('experience')
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Verificar que la reserva esté completada
        if ($booking->status !== 'completed') {
            return redirect()->route('bookings.index')->with('error', 'Solo puedes dejar reseñas de experiencias completadas.');
        }

        // Verificar si ya existe una reseña para esta reserva
        $existingReview = Review::where('booking_id', $booking->id)->exists();
        if ($existingReview) {
            return redirect()->route('bookings.index')->with('warning', 'Ya has dejado una reseña para esta experiencia.');
        }

        return view('reviews.create', compact('booking'));
    }

    /**
     * Almacena una nueva reseña en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:5000',
        ]);

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Doble verificación del estado
        if ($booking->status !== 'completed') {
            return redirect()->route('bookings.index')->with('error', 'Solo puedes dejar reseñas de experiencias completadas.');
        }

        // Doble verificación de reseña existente
        $existingReview = Review::where('booking_id', $booking->id)->exists();
        if ($existingReview) {
            return redirect()->route('bookings.index')->with('warning', 'Ya has dejado una reseña para esta experiencia.');
        }

        // Crear la reseña
        $review = Review::create([
            'user_id' => Auth::id(),
            'experience_id' => $booking->experience_id,
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Notificar al guía sobre la nueva reseña
        NotificationHelper::newReview($booking->experience->user, $review);

        return redirect()->route('bookings.index')->with('success', '¡Gracias por tu reseña!');
    }
}

