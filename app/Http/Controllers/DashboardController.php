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
    public function index(Request $request)
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

            // Cargar las reservas recibidas para las experiencias de este guía
            $query = $user->guideBookings()
                ->with(['user', 'experience', 'availabilitySlot']); // Cargar relaciones necesarias

            // --- FILTRO 1: Búsqueda por texto (Nombre turista o Título experiencia) ---
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhereHas('experience', function($subQ) use ($search) {
                            $subQ->where('title', 'like', "%{$search}%");
                        });
                });
            }

            // --- FILTRO 2: Estado de la reserva ---
            if ($request->filled('status')) {
                // CORRECCIÓN AQUI: Usar 'bookings.status' para evitar ambigüedad con 'experiences.status'
                $query->where('bookings.status', $request->input('status'));
            }

            // --- ORDENAMIENTO: Descendente (Nuevas primero) ---
            // Usamos bookings.created_at para evitar ambigüedad también en el ordenamiento
            $guideBookings = $query->orderBy('bookings.created_at', 'desc')
                ->paginate(10)
                ->withQueryString(); // Mantiene los filtros en la paginación

            return view('dashboard.guide', compact('experiences', 'guideBookings'));
        }

        // --- Panel del Turista ---
        if ($user->role === 'tourist') {
            return redirect()->route('home');
        }

        if ($user->role === 'admin') {
            return redirect()->route('home');
        }

        return view('dashboard');
    }
}
