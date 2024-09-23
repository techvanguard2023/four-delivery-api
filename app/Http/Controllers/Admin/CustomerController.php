<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\UserRoleService;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return Customer::paginate(25); // Paginação direta
        } else {
            return Customer::where('company_id', $user->company_id)->paginate(25); // Paginação após a query
        }
    }


    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers,phone'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

        // Inicia a transação
        $customer = DB::transaction(function () use ($validatedData, $request) {
            // Cria o cliente
            $customer = Customer::create($validatedData);

            // Verifica se o request possui o campo 'address' e cria o endereço de entrega
            if ($request->has('address')) {
                $customer->deliveryAddresses()->create($request->address);
            }

            return $customer;
        });

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


    public function SearchCustomer(Request $request)
    {
        var_dump('ok');

        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user);

        // Verifica se o campo 'name' ou 'phone' foi passado na requisição
        $query = Customer::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('phone')) {
            $query->orWhere('phone', 'like', '%' . $request->phone . '%');
        }

        // Se o usuário não for um admin, filtra também pela empresa
        if ($roleId != 1) {
            $query->where('company_id', $user->company_id);
        }

        return $query->paginate(25);
    }
}
