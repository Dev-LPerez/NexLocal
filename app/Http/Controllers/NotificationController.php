<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Obtener todas las notificaciones del usuario autenticado
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Obtener las notificaciones no leídas (para el dropdown)
     */
    public function unread()
    {
        $notifications = Auth::user()->notifications()
            ->where('is_read', false)
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotificationsCount()
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }
}
