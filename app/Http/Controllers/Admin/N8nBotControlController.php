<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class N8nBotControlController extends Controller
{
    public function index()
    {
        $user = $request->user();

        $data = N8nBotControl::query()
            ->where('company_id', $user->company_id)
            ->with('n8nBotControlItems') // Carrega os itens do pedido
            ->get(); // Executa a query

        return response()->json($data);
    }
}
