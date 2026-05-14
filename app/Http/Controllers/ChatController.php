<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Order;
use App\Models\ChatMessage;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Obtener todas las conversaciones del usuario (reservas y órdenes)
     */
    public function getConversations()
    {
        $user = Auth::user();
        $conversations = [];

        if ($user->role === 'tourist') {
            // --- 1. Obtener reservas del turista ---
            $bookings = Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
                ->with(['experience.user', 'availabilitySlot'])
                ->get();

            foreach ($bookings as $booking) {
                if ($this->hasVisibleMessages($user, 'booking_id', $booking->id)) {
                    $otherUser = $booking->experience->user;
                    $conversations[] = $this->formatConversationData($user, 'booking', $booking->id, $otherUser, $booking->experience->title, $booking->status);
                }
            }

            // --- 2. Obtener órdenes del turista ---
            $orders = Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'preparing', 'ready', 'delivered'])
                ->with(['localBusiness.user'])
                ->get();

            foreach ($orders as $order) {
                if ($this->hasVisibleMessages($user, 'order_id', $order->id)) {
                    $otherUser = $order->localBusiness->user;
                    $conversations[] = $this->formatConversationData($user, 'order', $order->id, $otherUser, 'Pedido en ' . $order->localBusiness->name, $order->status);
                }
            }

        } else if ($user->role === 'guide') {
            // --- Obtener reservas de experiencias del guía ---
            $bookings = Booking::whereHas('experience', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
            ->with(['user', 'experience', 'availabilitySlot'])
            ->get();

            foreach ($bookings as $booking) {
                if ($this->hasVisibleMessages($user, 'booking_id', $booking->id)) {
                    $otherUser = $booking->user;
                    $conversations[] = $this->formatConversationData($user, 'booking', $booking->id, $otherUser, $booking->experience->title, $booking->status);
                }
            }
        } else if ($user->role === 'owner') {
            // --- Obtener órdenes del negocio del dueño ---
            $orders = Order::whereHas('localBusiness', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['pending', 'preparing', 'ready', 'delivered'])
            ->with(['user', 'localBusiness'])
            ->get();

            foreach ($orders as $order) {
                if ($this->hasVisibleMessages($user, 'order_id', $order->id)) {
                    $otherUser = $order->user;
                    $conversations[] = $this->formatConversationData($user, 'order', $order->id, $otherUser, 'Pedido de ' . $otherUser->name, $order->status);
                }
            }
        }

        return response()->json(['conversations' => collect($conversations)->sortByDesc(function($conv) {
            return $conv['last_message'] ? strtotime($conv['last_message']['created_at_raw']) : 0;
        })->values()->all()]);
    }

    private function hasVisibleMessages($user, $columnName, $id)
    {
        return ChatMessage::where($columnName, $id)
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->where('hidden_for_sender', false);
                })->orWhere(function($q) use ($user) {
                    $q->where('receiver_id', $user->id)->where('hidden_for_receiver', false);
                });
            })
            ->exists();
    }

    private function formatConversationData($user, $type, $id, $otherUser, $title, $status)
    {
        $columnName = $type === 'booking' ? 'booking_id' : 'order_id';

        $unreadCount = ChatMessage::where($columnName, $id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->where('hidden_for_receiver', false)
            ->count();

        $lastMessage = ChatMessage::where($columnName, $id)
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->where('hidden_for_sender', false);
                })->orWhere(function($q) use ($user) {
                    $q->where('receiver_id', $user->id)->where('hidden_for_receiver', false);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            'type' => $type, // 'booking' or 'order'
            'id' => $id,
            'booking_id' => $type . '_' . $id, // Unique key for frontend loop compatibility
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'profile_photo_path' => $otherUser->profile_photo_path,
            ],
            'experience_title' => $title,
            'booking_status' => $status,
            'unread_count' => $unreadCount,
            'last_message' => $lastMessage ? [
                'message' => $lastMessage->message,
                'created_at' => $lastMessage->created_at->diffForHumans(),
                'created_at_raw' => $lastMessage->created_at, // For sorting
            ] : null,
        ];
    }

    /**
     * Obtener mensajes de una conversación específica (Booking)
     */
    public function getMessages($bookingId)
    {
        $user = Auth::user();
        $booking = Booking::with(['experience.user', 'user', 'availabilitySlot'])->findOrFail($bookingId);

        $isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
                       ($user->role === 'guide' && $booking->experience->user_id === $user->id);

        if (!$isAuthorized) abort(403, 'No tienes acceso a esta conversación.');

        $messages = $this->getVisibleMessages($user, 'booking_id', $bookingId);
        $this->markAsRead($user, 'booking_id', $bookingId);

        $otherUser = $user->role === 'tourist' ? $booking->experience->user : $booking->user;

        $date = null; $time = null;
        if ($booking->availabilitySlot && $booking->availabilitySlot->start_time) {
            $startDateTime = \Carbon\Carbon::parse($booking->availabilitySlot->start_time);
            $date = $startDateTime->format('d/m/Y'); $time = $startDateTime->format('H:i');
        } else if ($booking->booking_date) {
            $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date);
            $date = $bookingDateTime->format('d/m/Y'); $time = $bookingDateTime->format('H:i');
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
     * Enviar un mensaje (Booking)
     */
    public function sendMessage(Request $request, $bookingId)
    {
        $request->validate(['message' => 'required|string|max:5000']);
        $user = Auth::user();
        $booking = Booking::with(['experience.user', 'user'])->findOrFail($bookingId);

        $isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
                       ($user->role === 'guide' && $booking->experience->user_id === $user->id);

        if (!$isAuthorized) abort(403, 'No tienes acceso a esta conversación.');

        $receiverId = $user->role === 'tourist' ? $booking->experience->user_id : $booking->user_id;

        $message = ChatMessage::create([
            'booking_id' => $bookingId,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        $message->load('sender', 'receiver');
        return response()->json(['message' => $message]);
    }

    public function deleteConversation($bookingId)
    {
        $this->hideConversation(Auth::user(), 'booking_id', $bookingId);
        return response()->json(['success' => true, 'message' => 'Conversación ocultada']);
    }

    // --- NUEVAS RUTAS PARA ÓRDENES (E-COMMERCE) ---

    public function getOrderMessages($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['localBusiness.user', 'user'])->findOrFail($orderId);

        $isAuthorized = ($user->role === 'tourist' && $order->user_id === $user->id) ||
                       ($user->role === 'owner' && $order->localBusiness->user_id === $user->id);

        if (!$isAuthorized) abort(403, 'No tienes acceso a esta conversación.');

        $messages = $this->getVisibleMessages($user, 'order_id', $orderId);
        $this->markAsRead($user, 'order_id', $orderId);

        $otherUser = $user->role === 'tourist' ? $order->localBusiness->user : $order->user;

        $bookingInfo = [
            'id' => $order->id,
            'experience_title' => 'Pedido en ' . $order->localBusiness->name,
            'status' => $order->status,
            'date' => $order->created_at->format('d/m/Y'),
            'time' => $order->created_at->format('H:i'),
            'num_travelers' => $order->items()->sum('quantity'), // usaremos num_travelers como items para reutilizar frontend
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

    public function sendOrderMessage(Request $request, $orderId)
    {
        $request->validate(['message' => 'required|string|max:5000']);
        $user = Auth::user();
        $order = Order::with(['localBusiness.user', 'user'])->findOrFail($orderId);

        $isAuthorized = ($user->role === 'tourist' && $order->user_id === $user->id) ||
                       ($user->role === 'owner' && $order->localBusiness->user_id === $user->id);

        if (!$isAuthorized) abort(403, 'No tienes acceso a esta conversación.');

        $receiverId = $user->role === 'tourist' ? $order->localBusiness->user_id : $order->user_id;

        $message = ChatMessage::create([
            'order_id' => $orderId,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        $message->load('sender', 'receiver');
        return response()->json(['message' => $message]);
    }

    public function deleteOrderConversation($orderId)
    {
        $this->hideConversation(Auth::user(), 'order_id', $orderId);
        return response()->json(['success' => true, 'message' => 'Conversación ocultada']);
    }

    // --- MÉTODOS AUXILIARES ---

    private function getVisibleMessages($user, $columnName, $id)
    {
        return ChatMessage::where($columnName, $id)
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->where('hidden_for_sender', false);
                })->orWhere(function($q) use ($user) {
                    $q->where('receiver_id', $user->id)->where('hidden_for_receiver', false);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    private function markAsRead($user, $columnName, $id)
    {
        ChatMessage::where($columnName, $id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->where('hidden_for_receiver', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    private function hideConversation($user, $columnName, $id)
    {
        ChatMessage::where($columnName, $id)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->each(function($message) use ($user) {
                if ($message->sender_id === $user->id) $message->update(['hidden_for_sender' => true]);
                if ($message->receiver_id === $user->id) $message->update(['hidden_for_receiver' => true]);
            });
    }

    public function getUnreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
