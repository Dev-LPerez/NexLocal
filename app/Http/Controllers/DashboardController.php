<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Experience;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard apropiado según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            // Si no hay usuario autenticado, redirigir al login
            return redirect()->route('login');
        }

        // --- Panel del Guía ---
        if ($user->role === 'guide') {
            // Cargar las experiencias creadas por este guía
            $experiences = $user->experiences()->latest()->get();

            // Cargar las reservas recibidas para las experiencias de este guía (con paginación)
            $guideBookings = $user->guideBookings()
                             ->with(['user', 'experience', 'availabilitySlot']) // Cargar el turista, experiencia y slot
                             ->latest('booking_date')
                             ->paginate(10);

            return view('dashboard.guide', compact('experiences', 'guideBookings'));
        }

        // --- Panel del Turista ---
        // Redirigimos al turista a su página dedicada "Mis Reservas"
        if ($user->role === 'tourist') {
            return redirect()->route('bookings.index');
        }

        // --- Fallback (ej. para Admin o si el rol no está definido) ---
        // Muestra el dashboard genérico de Breeze
        return view('dashboard');
    }
}
