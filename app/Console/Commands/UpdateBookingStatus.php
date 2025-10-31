<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-booking-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de las reservas a "completado" si la fecha del slot ya pasó.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Iniciando actualización de estados de reserva...');

        $now = Carbon::now();

        // Buscamos reservas 'confirmed'
        $bookingsToUpdate = Booking::where('status', 'confirmed')
            ->whereHas('availabilitySlot', function ($query) use ($now) {
                // Cuya fecha/hora de inicio (start_time) ya pasó
                $query->where('start_time', '<=', $now);
            })
            ->get();

        if ($bookingsToUpdate->isEmpty()) {
            $this->info('No hay reservas para actualizar.');
            return;
        }

        $count = 0;
        foreach ($bookingsToUpdate as $booking) {
            $booking->status = 'completed';
            $booking->save();
            $count++;
        }

        $this->info("¡Actualización completada! {$count} reservas marcadas como 'completed'.");
    }
}

