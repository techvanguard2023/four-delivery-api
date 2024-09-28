<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ViaCepController extends Controller
{
    public function search($zipCode)
    {
        if (!preg_match('/^[0-9]{8}$/', $zipCode)) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        $url = "https://viacep.com.br/ws/$zipCode/json/";
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['erro']) && $data['erro'] === true) {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }

            return $data;
        }

        return response()->json(['error' => 'Erro ao consultar o CEP'], 500);
    }
}
