<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos usuarios para crear notificaciones de ejemplo
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios en la base de datos. Ejecuta primero DatabaseSeeder.');
            return;
        }

        foreach ($users as $user) {
            // Crear 2-3 notificaciones para cada usuario
            Notification::create([
                'user_id' => $user->id,
                'type' => 'welcome',
                'title' => '¡Bienvenido a NexLocal!',
                'message' => 'Gracias por unirte a nuestra plataforma de experiencias auténticas.',
                'icon' => '👋',
                'link' => route('home'),
            ]);

            if ($user->role === 'tourist') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Descubre nuevas experiencias',
                    'message' => 'Explora las experiencias locales más auténticas en tu área.',
                    'icon' => '🗺️',
                    'link' => route('home'),
                    'is_read' => false,
                ]);
            } else if ($user->role === 'guide') {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Crea tu primera experiencia',
                    'message' => 'Comparte tus conocimientos locales y crea experiencias únicas para los viajeros.',
                    'icon' => '✨',
                    'link' => route('experiences.create'),
                    'is_read' => false,
                ]);
            }
        }

        $this->command->info('Notificaciones de ejemplo creadas exitosamente.');
    }
}

