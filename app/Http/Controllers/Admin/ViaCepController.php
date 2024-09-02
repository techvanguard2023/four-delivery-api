<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ViaCepController extends Controller
{
    public function search($zipCode)
    {
        // Validar formato do CEP
        if (!preg_match('/^[0-9]{8}$/', $zipCode)) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        $url = "https://viacep.com.br/ws/$zipCode/json/";
        $response = Http::get($url);

        // Verificar se a resposta foi bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Verificar se o CEP foi encontrado
            if (isset($data['erro']) && $data['erro'] === true) {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }

            return $data;
        }

        // Retornar erro se a requisição falhar
        return response()->json(['error' => 'Erro ao consultar o CEP'], 500);
    }
}