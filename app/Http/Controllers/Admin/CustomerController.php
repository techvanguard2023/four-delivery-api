<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

use App\Services\UserRoleService;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return Customer::all();
        } else {
            return Customer::where('company_id', $user->company_id)->get();
        }
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

        $customer = Customer::create($validatedData);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return $customer;
    }

    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string'
        ]);

        $customer->update($validatedData);

        return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(null, 204);
    }
}
