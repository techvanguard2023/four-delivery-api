<?php

namespace App\Services\Reports;

use App\Models\Reservation;
use Carbon\Carbon;

class ReservationReportService
{
    public function getTodayReservations()
    {
        $companyId = auth()->user()->company_id;

        // Filtrar reservas do dia atual
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $reservations = Reservation::where('company_id', $companyId)
            ->whereBetween('reserved_at', [$today, $tomorrow])
            ->orderBy('reserved_at')
            ->get(['customer_name', 'contact_phone', 'position', 'reserved_at']);

        return [
            'total' => $reservations->count(),
            'data' => $reservations->map(function ($reservation) {
                return [
                    'name' => $reservation->customer_name,
                    'phone' => $reservation->contact_phone,
                    'table' => $reservation->position,
                    'time' => $reservation->reserved_at->format('H:i'),
                ];
            }),
        ];
    }
}