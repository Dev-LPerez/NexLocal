<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\ChatMessage;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Obtener todas las conversaciones del usuario (reservas con las que puede chatear)
     */
    public function getConversations()
    {
        $user = Auth::user();
        $conversations = [];

        if ($user->role === 'tourist') {
            // Obtener reservas del turista que no estén canceladas
            $bookings = Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
                ->with(['experience.user', 'availabilitySlot'])
                ->get();

            foreach ($bookings as $booking) {
                // Verificar si hay mensajes visibles para este usuario
                $hasVisibleMessages = ChatMessage::where('booking_id', $booking->id)
                    ->where(function($query) use ($user) {
                        $query->where(function($q) use ($user) {
                            $q->where('sender_id', $user->id)
                              ->where('hidden_for_sender', false);
                        })->orWhere(function($q) use ($user) {
                            $q->where('receiver_id', $user->id)
                              ->where('hidden_for_receiver', false);
                        });
                    })
                    ->exists();

                // Si no hay mensajes visibles, saltar esta conversación
                if (!$hasVisibleMessages) {
                    continue;
                }

                $otherUser = $booking->experience->user;

                // Contar solo mensajes no leídos y visibles
                $unreadCount = ChatMessage::where('booking_id', $booking->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->where('hidden_for_receiver', false)
                    ->count();

                // Obtener último mensaje visible
                $lastMessage = ChatMessage::where('booking_id', $booking->id)
                    ->where(function($query) use ($user) {
                        $query->where(function($q) use ($user) {
                            $q->where('sender_id', $user->id)
                              ->where('hidden_for_sender', false);
                        })->orWhere(function($q) use ($user) {
                            $q->where('receiver_id', $user->id)
                              ->where('hidden_for_receiver', false);
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                $conversations[] = [
                    'booking_id' => $booking->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'profile_photo_path' => $otherUser->profile_photo_path,
                    ],
                    'experience_title' => $booking->experience->title,
                    'booking_status' => $booking->status,
                    'unread_count' => $unreadCount,
                    'last_message' => $lastMessage ? [
                        'message' => $lastMessage->message,
                        'created_at' => $lastMessage->created_at->diffForHumans(),
                    ] : null,
                ];
            }
        } else if ($user->role === 'guide') {
            // Obtener reservas de experiencias del guía que no estén canceladas
            $bookings = Booking::whereHas('experience', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
            ->with(['user', 'experience', 'availabilitySlot'])
            ->get();

            foreach ($bookings as $booking) {
                // Verificar si hay mensajes visibles para este usuario
                $hasVisibleMessages = ChatMessage::where('booking_id', $booking->id)
                    ->where(function($query) use ($user) {
                        $query->where(function($q) use ($user) {
                            $q->where('sender_id', $user->id)
                              ->where('hidden_for_sender', false);
                        })->orWhere(function($q) use ($user) {
                            $q->where('receiver_id', $user->id)
                              ->where('hidden_for_receiver', false);
                        });
                    })
                    ->exists();

                // Si no hay mensajes visibles, saltar esta conversación
                if (!$hasVisibleMessages) {
                    continue;
                }

                $otherUser = $booking->user;

                // Contar solo mensajes no leídos y visibles
                $unreadCount = ChatMessage::where('booking_id', $booking->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->where('hidden_for_receiver', false)
                    ->count();

                // Obtener último mensaje visible
                $lastMessage = ChatMessage::where('booking_id', $booking->id)
                    ->where(function($query) use ($user) {
                        $query->where(function($q) use ($user) {
                            $q->where('sender_id', $user->id)
                              ->where('hidden_for_sender', false);
                        })->orWhere(function($q) use ($user) {
                            $q->where('receiver_id', $user->id)
                              ->where('hidden_for_receiver', false);
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                $conversations[] = [
                    'booking_id' => $booking->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'profile_photo_path' => $otherUser->profile_photo_path,
                    ],
                    'experience_title' => $booking->experience->title,
                    'booking_status' => $booking->status,
                    'unread_count' => $unreadCount,
                    'last_message' => $lastMessage ? [
                        'message' => $lastMessage->message,
                        'created_at' => $lastMessage->created_at->diffForHumans(),
                    ] : null,
                ];
            }
        }

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * Obtener mensajes de una conversación específica
     */
    public function getMessages($bookingId)
    {
        $user = Auth::user();

        // Verificar que el usuario tenga acceso a esta conversación
        $booking = Booking::with(['experience.user', 'user', 'availabilitySlot'])
            ->findOrFail($bookingId);

        $isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
                       ($user->role === 'guide' && $booking->experience->user_id === $user->id);

        if (!$isAuthorized) {
            abort(403, 'No tienes acceso a esta conversación.');
        }

        // Obtener solo mensajes visibles para el usuario actual
        $messages = ChatMessage::where('booking_id', $bookingId)
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    // Mensajes que envió y no ha ocultado
                    $q->where('sender_id', $user->id)
                      ->where('hidden_for_sender', false);
                })->orWhere(function($q) use ($user) {
                    // Mensajes que recibió y no ha ocultado
                    $q->where('receiver_id', $user->id)
                      ->where('hidden_for_receiver', false);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar mensajes como leídos (solo los que recibió y son visibles)
        ChatMessage::where('booking_id', $bookingId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->where('hidden_for_receiver', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Determinar el otro usuario
        $otherUser = $user->role === 'tourist' ? $booking->experience->user : $booking->user;

        // Información de la reserva con manejo mejorado de fecha/hora
        $date = null;
        $time = null;

        if ($booking->availabilitySlot && $booking->availabilitySlot->start_time) {
            // start_time es un dateTime completo
            try {
                $startDateTime = \Carbon\Carbon::parse($booking->availabilitySlot->start_time);
                $date = $startDateTime->format('d/m/Y');
                $time = $startDateTime->format('H:i');
            } catch (\Exception $e) {
                \Log::error('Error parseando start_time del slot: ' . $e->getMessage());
            }
        } else if ($booking->booking_date) {
            // Si no hay slot pero hay booking_date (campo alternativo)
            try {
                $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date);
                $date = $bookingDateTime->format('d/m/Y');
                $time = $bookingDateTime->format('H:i');
            } catch (\Exception $e) {
                \Log::error('Error parseando booking_date: ' . $e->getMessage());
            }
        }

        $bookingInfo = [
            'id' => $booking->id,
            'experience_title' => $booking->experience->title,
            'status' => $booking->status,
            'date' => $date,
            'time' => $time,
            'num_travelers' => $booking->num_travelers,
        ];

        return response()->json([
            'messages' => $messages,
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'profile_photo_path' => $otherUser->profile_photo_path,
            ],
            'booking_info' => $bookingInfo,
        ]);
    }

    /**
     * Enviar un mensaje
     */
    public function sendMessage(Request $request, $bookingId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = Auth::user();

        // Verificar acceso
        $booking = Booking::with(['experience.user', 'user'])->findOrFail($bookingId);

        $isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
                       ($user->role === 'guide' && $booking->experience->user_id === $user->id);

        if (!$isAuthorized) {
            abort(403, 'No tienes acceso a esta conversación.');
        }

        // Determinar el receptor
        $receiverId = $user->role === 'tourist'
            ? $booking->experience->user_id
            : $booking->user_id;

        // Crear el mensaje
        $message = ChatMessage::create([
            'booking_id' => $bookingId,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        $message->load('sender', 'receiver');

        return response()->json(['message' => $message]);
    }

    /**
     * Obtener el contador total de mensajes no leídos
     */
    public function getUnreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Ocultar una conversación solo para el usuario actual
     */
    public function deleteConversation($bookingId)
    {
        $user = Auth::user();

        // Verificar que el usuario tenga acceso a esta conversación
        $booking = Booking::with(['experience.user', 'user'])->findOrFail($bookingId);

        $isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
                       ($user->role === 'guide' && $booking->experience->user_id === $user->id);

        if (!$isAuthorized) {
            abort(403, 'No tienes acceso a esta conversación.');
        }

        // Marcar mensajes como ocultos para el usuario actual
        // Si el usuario es el sender, marcamos hidden_for_sender
        // Si el usuario es el receiver, marcamos hidden_for_receiver
        ChatMessage::where('booking_id', $bookingId)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->each(function($message) use ($user) {
                if ($message->sender_id === $user->id) {
                    $message->update(['hidden_for_sender' => true]);
                }
                if ($message->receiver_id === $user->id) {
                    $message->update(['hidden_for_receiver' => true]);
                }
            });

        return response()->json(['success' => true, 'message' => 'Conversación ocultada']);
    }
}
