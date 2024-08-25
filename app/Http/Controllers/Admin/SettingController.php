<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Método para obter uma configuração específica por chave
    public function show($key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['error' => 'Configuração não encontrada'], 404);
        }

        return response()->json($setting, 200);
    }

    // Método para atualizar uma configuração específica por chave
    public function update(Request $request, $key)
    {
        $request->validate([
            'value' => 'required', // Validar que o valor é obrigatório
        ]);

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->input('value')]
        );

        return response()->json($setting, 200);
    }

    // Método para listar todas as configurações
    public function index()
    {
        $settings = Setting::all();
        return response()->json($settings, 200);
    }
}
