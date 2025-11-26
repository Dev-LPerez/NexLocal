<?php
// Archivo: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\NotificationHelper;

class AdminController extends Controller
{
    /**
     * Muestra el Dashboard principal del Administrador con estadísticas clave.
     */
    public function index()
    {
        // Recopilamos estadísticas generales del sistema
        $stats = [
            'total_users' => User::count(),
            'total_guides' => User::where('role', 'guide')->count(),
            // Contamos guías con verificación pendiente
            'pending_verifications' => User::where('role', 'guide')
                ->where('verification_status', 'pending')
                ->count(),
            'total_bookings' => Booking::count(),
            'active_experiences' => Experience::count(),
            // Suma simple de ingresos de reservas completadas
            'revenue' => Booking::where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Muestra la lista de guías que están esperando verificación.
     */
    public function verificationQueue()
    {
        // Obtenemos los guías pendientes de verificación
        $pendingGuides = User::where('role', 'guide')
            ->where('verification_status', 'pending')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.verify-guides', compact('pendingGuides'));
    }

    /**
     * Aprueba la verificación de un guía.
     */
    public function approveGuide($id)
    {
        $guide = User::findOrFail($id);

        // Verificar que sea un guía pendiente
        if ($guide->role !== 'guide' || $guide->verification_status !== 'pending') {
            return redirect()->back()->with('error', 'Esta solicitud no está disponible para aprobación.');
        }

        // Aprobar verificación
        $guide->identity_verified_at = now();
        $guide->verification_status = 'approved';
        $guide->rejection_reason = null;
        $guide->save();

        // Notificar al guía
        NotificationHelper::create(
            $guide->id,
            '✅ ¡Verificación Aprobada!',
            'Tu identidad ha sido verificada exitosamente. Ya puedes crear experiencias turísticas.',
            'verification_approved',
            route('experiences.create')
        );

        return redirect()->back()->with('success', '¡Guía verificado! ' . $guide->name . ' puede ahora crear experiencias.');
    }

    /**
     * Rechaza la verificación de un guía.
     */
    public function rejectGuide(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ], [
            'rejection_reason.required' => 'Debes proporcionar una razón para el rechazo.'
        ]);

        $guide = User::findOrFail($id);

        // Verificar que sea un guía pendiente
        if ($guide->role !== 'guide' || $guide->verification_status !== 'pending') {
            return redirect()->back()->with('error', 'Esta solicitud no está disponible para rechazo.');
        }

        // Rechazar verificación
        $guide->verification_status = 'rejected';
        $guide->rejection_reason = $request->rejection_reason;
        $guide->identity_verified_at = null;
        $guide->save();

        // Notificar al guía
        NotificationHelper::create(
            $guide->id,
            '❌ Verificación Rechazada',
            'Tu solicitud de verificación fue rechazada. Por favor, revisa la razón y envía nuevos documentos.',
            'verification_rejected',
            route('verification.create')
        );

        return redirect()->back()->with('success', 'Solicitud rechazada. El guía ha sido notificado.');
    }

    /**
     * Permite al administrador descargar y visualizar el documento de identidad.
     */
    public function downloadDocument($id, $type = 'front')
    {
        $guide = User::findOrFail($id);

        // Determinar qué documento descargar
        $documentPath = $type === 'back'
            ? $guide->identity_document_back_path
            : $guide->identity_document_path;

        if (!$documentPath) {
            return back()->with('error', 'Este documento no está disponible.');
        }

        // Verificamos si el archivo existe en el disco 'private'
        if (!Storage::disk('private')->exists($documentPath)) {
            return back()->with('error', 'El archivo no se encuentra en el servidor.');
        }

        // Forzamos la descarga o visualización segura del archivo
        return Storage::disk('private')->download($documentPath);
    }

    // ==================== GESTIÓN DE USUARIOS ====================

    /**
     * Muestra listado de todos los usuarios con búsqueda
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Búsqueda por email o nombre
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filtro por suspendidos
        if ($request->has('suspended') && $request->suspended == '1') {
            $query->where('is_suspended', true);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Suspender cuenta de usuario
     */
    public function suspendUser(Request $request, $id)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500'
        ]);

        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'No puedes suspender a otro administrador.');
        }

        $user->suspend($request->suspension_reason);

        // Notificar al usuario
        NotificationHelper::create(
            $user->id,
            '🚫 Cuenta Suspendida',
            "Tu cuenta ha sido suspendida. Razón: {$request->suspension_reason}",
            'account_suspended',
            null
        );

        return back()->with('success', "Usuario {$user->name} ha sido suspendido.");
    }

    /**
     * Restaurar cuenta de usuario
     */
    public function restoreUser($id)
    {
        $user = User::findOrFail($id);
        $user->restore();

        // Notificar al usuario
        NotificationHelper::create(
            $user->id,
            '✅ Cuenta Restaurada',
            'Tu cuenta ha sido restaurada. Ahora puedes acceder normalmente.',
            'account_restored',
            route('home')
        );

        return back()->with('success', "Usuario {$user->name} ha sido restaurado.");
    }

    /**
     * Cambiar rol de usuario
     */
    public function changeUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:tourist,guide,admin'
        ]);

        $user = User::findOrFail($id);
        $oldRole = $user->role;
        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Rol cambiado de {$oldRole} a {$request->role}.");
    }

    // ==================== MODERACIÓN DE EXPERIENCIAS ====================

    /**
     * Listado de todas las experiencias
     */
    public function experiences(Request $request)
    {
        $query = Experience::with('user');

        // Filtro por estado
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filtro por destacadas
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Búsqueda por título
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $experiences = $query->latest()->paginate(15);

        return view('admin.experiences.index', compact('experiences'));
    }

    /**
     * Cambiar estado de experiencia
     */
    public function changeExperienceStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,published,hidden,rejected',
            'moderation_note' => 'nullable|string|max:500'
        ]);

        $experience = Experience::findOrFail($id);
        $experience->status = $request->status;
        $experience->moderation_note = $request->moderation_note;
        $experience->save();

        // Notificar al guía
        if (in_array($request->status, ['hidden', 'rejected'])) {
            NotificationHelper::create(
                $experience->user_id,
                '⚠️ Experiencia Moderada',
                "Tu experiencia '{$experience->title}' ha sido marcada como {$request->status}. Nota: {$request->moderation_note}",
                'experience_moderated',
                route('experiences.edit', $experience->id)
            );
        }

        return back()->with('success', 'Estado de experiencia actualizado.');
    }

    /**
     * Destacar/Desdestacar experiencia
     */
    public function toggleFeatured($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->is_featured = !$experience->is_featured;
        $experience->save();

        $status = $experience->is_featured ? 'destacada' : 'no destacada';
        return back()->with('success', "Experiencia marcada como {$status}.");
    }

    // ==================== MODERACIÓN DE RESEÑAS ====================

    /**
     * Listado de todas las reseñas
     */
    public function reviews(Request $request)
    {
        $query = \App\Models\Review::with(['user', 'experience']);

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $query->where('comment', 'like', "%{$request->search}%");
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Eliminar reseña inapropiada
     */
    public function deleteReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);

        // Notificar al usuario
        NotificationHelper::create(
            $review->user_id,
            '🗑️ Reseña Eliminada',
            "Tu reseña en '{$review->experience->title}' ha sido eliminada por violar las políticas de la comunidad.",
            'review_deleted',
            null
        );

        $review->delete();

        return back()->with('success', 'Reseña eliminada correctamente.');
    }

    // ==================== AUDITORÍA Y LOGS ====================

    /**
     * Historial completo de reservas (super tabla)
     */
    public function bookingsAudit(Request $request)
    {
        $query = Booking::with(['user', 'experience', 'availabilitySlot']);

        // Filtros
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest('booking_date')->paginate(30);

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.audit.bookings', compact('bookings', 'stats'));
    }
}
