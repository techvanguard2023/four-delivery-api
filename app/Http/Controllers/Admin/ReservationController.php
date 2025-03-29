<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $date = $request->input('date'); // formato: Y-m-d

        $query = Reservation::query()
            ->where('company_id', $user->company_id);


        return response()->json($query->get());
    }


    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'position' => 'required|string',
            'customer_name' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'observation' => 'nullable|string',
            'reserved_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:15',
        ]);


        // Pega as configurações do cliente
        $setting = DB::table('settings')
            ->where('company_id', $user->company_id)
            ->first();


        if (! $setting || ! isset($setting->data)) {
            return response()->json([
                'message' => 'Configuração do sistema não encontrada.',
            ], 500);
        }

        $config = json_decode($setting->data, true);

        if (! isset($config['total_tables']) || ! is_numeric($config['total_tables'])) {
            return response()->json([
                'message' => 'Número total de mesas não configurado corretamente.',
            ], 500);
        }

        // Valida se a mesa informada está dentro do intervalo permitido
        $mesaNumero = (int) preg_replace('/\D/', '', $data['position']);
        $totalTables = (int) $config['total_tables'];

        if ($mesaNumero < 1 || $mesaNumero > $totalTables) {
            return response()->json([
                'message' => 'Número da mesa inválido.',
            ], 422);
        }

        // Verifica se já existe uma reserva ativa para essa mesa
        $alreadyReserved = Reservation::where('position', $data['position'])
            ->where('company_id', $user->company_id)
            ->where('status', '!=', 'cancelled')
            ->whereNull('deleted_at')
            ->exists();

        if ($alreadyReserved) {
            return response()->json([
                'message' => 'Já existe uma reserva pendente ou confirmada para esta mesa.',
            ], 422);
        }


        if (!empty($data['contact_phone'])) {
            $existingReservation = Reservation::where('contact_phone', $data['contact_phone'])
                ->where('company_id', $user->company_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereNull('deleted_at')
                ->first();

            if ($existingReservation) {
                return response()->json([
                    'message' => 'Este cliente (Número de celular) já possui uma reserva ' . $existingReservation->status . ' na ' . $existingReservation->position . '.',
                ], 422);
            }
        }



        // Cria a reserva
        $data['company_id'] = $user->company_id;
        $data['status'] = 'pending';

        $reservation = Reservation::create($data);

        return response()->json($reservation, 201);
    }



    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $reservation->update($data);

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Atualiza o status antes de deletar
        $reservation->status = 'cancelled';
        $reservation->save();

        // Soft delete
        $reservation->delete();

        return response()->json(['message' => 'Reserva cancelada com sucesso.']);
    }
}
