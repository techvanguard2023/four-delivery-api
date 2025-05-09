<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\UserRoleService;

class SettingController extends Controller
{
    // Método para obter uma configuração específica por chave
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user);

        if ($roleId == 1) {
            return Setting::all();
        } else {
            return Setting::where('company_id', $user->company_id)->get();
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|array',
            'company_id' => 'required|integer',
        ]);

        $setting = Setting::create($validatedData);

        return response()->json($setting, 201);
    }

    public function show(Setting $setting)
    {
        return $setting;
    }

    // Método para atualizar uma configuração específica por chave
    public function updateByKey(Request $request, $key)
    {
        $user = $request->user();

        $request->validate([
            'value' => 'required', // Pode ser valor simples ou array (ajuste conforme o tipo)
        ]);

        $setting = Setting::where('company_id', $user->company_id)->first();

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $data = json_decode($setting->data, true); // Decodifica JSON atual
        $data[$key] = $request->input('value');    // Altera só a chave desejada

        $setting->data = json_encode($data);       // Codifica novamente
        $setting->save();

        return response()->json($setting, 200);
    }
}
