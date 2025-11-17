<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\ChatMessage;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunas reservas activas
        $bookings = Booking::whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->with(['user', 'experience.user'])
            ->take(5)
            ->get();

        if ($bookings->isEmpty()) {
            $this->command->warn('No hay reservas activas para crear mensajes de chat.');
            return;
        }

        $conversationExamples = [
            [
                ['sender' => 'guide', 'message' => '¡Hola! Gracias por reservar mi experiencia. ¿Tienes alguna pregunta?'],
                ['sender' => 'tourist', 'message' => 'Hola, sí. ¿Qué debo llevar para la actividad?'],
                ['sender' => 'guide', 'message' => 'Te recomiendo ropa cómoda y agua. Si hace sol, trae protector solar.'],
                ['sender' => 'tourist', 'message' => 'Perfecto, gracias por la información.'],
            ],
            [
                ['sender' => 'tourist', 'message' => 'Hola, ¿a qué hora nos encontramos exactamente?'],
                ['sender' => 'guide', 'message' => 'Hola! Nos vemos a las 10:00 AM en el punto de encuentro que te indiqué.'],
                ['sender' => 'tourist', 'message' => '¿Es posible llegar un poco antes?'],
                ['sender' => 'guide', 'message' => 'Sí, claro. Puedes llegar desde las 9:45 AM sin problema.'],
            ],
            [
                ['sender' => 'guide', 'message' => 'Hola, confirmo tu reserva para mañana. ¿Todo listo?'],
                ['sender' => 'tourist', 'message' => '¡Sí! Muy emocionado por la experiencia.'],
                ['sender' => 'guide', 'message' => 'Genial, será una gran experiencia. ¡Nos vemos mañana!'],
            ],
        ];

        foreach ($bookings as $index => $booking) {
            $guide = $booking->experience->user;
            $tourist = $booking->user;

            // Usar ejemplo de conversación o mensajes simples
            $messages = $conversationExamples[$index % count($conversationExamples)] ?? [
                ['sender' => 'guide', 'message' => 'Hola, ¿cómo estás?'],
                ['sender' => 'tourist', 'message' => 'Bien, gracias. Esperando la experiencia.'],
            ];

            foreach ($messages as $msg) {
                $senderId = $msg['sender'] === 'guide' ? $guide->id : $tourist->id;
                $receiverId = $msg['sender'] === 'guide' ? $tourist->id : $guide->id;

                ChatMessage::create([
                    'booking_id' => $booking->id,
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'message' => $msg['message'],
                    'is_read' => rand(0, 1) == 1, // Aleatorio
                    'read_at' => rand(0, 1) == 1 ? now() : null,
                    'created_at' => now()->subMinutes(rand(5, 120)),
                ]);
            }
        }

        $this->command->info('Mensajes de chat de ejemplo creados exitosamente.');
    }
}
