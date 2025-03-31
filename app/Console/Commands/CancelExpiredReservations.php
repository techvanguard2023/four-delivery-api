<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelExpiredReservations extends Command
{
    protected $signature = 'reservations:cancel-expired';
    protected $description = 'Cancela reservas que passaram do tempo';

    public function handle()
    {
        $now = Carbon::now();

        // Log pra ver se o schedule está rodando
        Log::channel('reservations')->info('🔁 Iniciando verificação de reservas expiradas no horário de ' . $now);

        $expiredReservations = Reservation::where('status', '!=', 'cancelled')
            ->where('reserved_at', '<=', $now) // Ajusta a comparação
            ->get();

        foreach ($expiredReservations as $reservation) {
            //$endTime = Carbon::parse($reservation->reserved_at)->addMinutes($reservation->duration_minutes);
            $endTime = Carbon::parse($reservation->reserved_at);

            // Verifica se o horário de término já passou
            if ($now->greaterThan($endTime)) {
                $reservation->status = 'cancelled';
                $reservation->save();
                $reservation->delete(); // soft delete

                $this->info("Reserva ID {$reservation->id} cancelada.");
                Log::channel('reservations')->info("✅ Reserva ID {$reservation->id} cancelada.");
            } else {
                $this->info("Reserva ID {$reservation->id} não foi cancelada.");
                Log::channel('reservations')->info("❌ Reserva ID {$reservation->id} não foi cancelada.");
            }
        }

        $this->info('Verificação concluída.');
        Log::channel('reservations')->info('✅ Verificação concluída.');
        return 0;
    }
}
