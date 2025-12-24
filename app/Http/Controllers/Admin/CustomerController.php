<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Services\UserRoleService;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="Listar clientes da empresa (paginado)",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista paginada de clientes")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return Customer::where('company_id', $user->company_id)
                ->with('deliveryAddresses') // Carrega os endereços de entrega
                ->paginate(25); // Paginação após a query
    }


    /**
     * @OA\Get(
     *     path="/customers-all",
     *     summary="Listar todos os clientes (com ou sem paginação dependendo do cargo)",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de clientes")
     * )
     */
    public function getCustomerWithoutPaginate(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return Customer::paginate(25); // Paginação direta
        } else {
            return Customer::where('company_id', $user->company_id)
                ->with('deliveryAddresses') // Carrega os endereços de entrega
                ->get(); // Paginação após a query
        }
    }


    /**
     * @OA\Post(
     *     path="/customers",
     *     summary="Cadastrar novo cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone","address","neighborhood","city","state","zip_code"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="neighborhood", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="state", type="string"),
     *             @OA\Property(property="zip_code", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cliente cadastrado")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Usuário autenticado

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers,phone',
            'email' => ['string', 'email', 'max:255', 'unique:customers'],
            'password' => ['string', 'min:6', 'confirmed'],
            'is_whatsapp' => 'boolean',
            'address' => 'required|string|max:255',
            'number' => 'string|max:10',
            'complement' => 'string|max:255|nullable',
            'neighborhood' => 'required|string|max:255',
            'reference_point' => 'string|max:255|nullable',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10'
        ]);

        $validatedData['company_id'] = $user->company_id;

        // Separar dados do cliente e do endereço
        $customerData = Arr::only($validatedData, ['company_id', 'name', 'phone', 'email', 'is_whatsapp']);
        $customerData['password'] = bcrypt($validatedData['password']);

        $addressData = Arr::only($validatedData, [
            'address', 'number', 'complement', 'neighborhood', 'reference_point', 'city', 'state', 'zip_code'
        ]);

        $customer = DB::transaction(function () use ($customerData, $addressData) {
            $customer = Customer::create($customerData);
            $customer->deliveryAddresses()->create($addressData);
            return $customer;
        });

        return response()->json($customer, 201);
    }




    /**
     * @OA\Get(
     *     path="/customers/{id}",
     *     summary="Mostrar detalhes de um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalhes do cliente")
     * )
     */
    public function show(Customer $customer)
    {
        // Carrega os endereços de entrega do cliente
        $customer->load('deliveryAddresses');

        return $customer;
    }


    /**
     * @OA\Put(
     *     path="/customers/{id}",
     *     summary="Atualizar um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Cliente atualizado")
     * )
     */
    public function update(Request $request, Customer $customer)
    {
        // Validação dos dados do cliente e dos endereços
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:customers,phone,' . $customer->id,
            'is_whatsapp' => 'sometimes|boolean',
            'addresses' => 'sometimes|array',
            'addresses.*.id' => 'sometimes|exists:delivery_addresses,id',
            'addresses.*.address' => 'required_with:addresses|string|max:255',
            'addresses.*.number' => 'nullable|string|max:20',
            'addresses.*.complement' => 'nullable|string|max:255',
            'addresses.*.neighborhood' => 'nullable|string|max:255',
            'addresses.*.reference_point' => 'nullable|string|max:255',
            'addresses.*.city' => 'nullable|string|max:255',
            'addresses.*.state' => 'nullable|string|max:255',
            'addresses.*.zip_code' => 'nullable|string|max:20',
        ]);

        // Separa os dados do cliente dos dados de endereço
        $customerData = collect($validatedData)->except('addresses')->toArray();

        DB::transaction(function () use ($customer, $customerData, $request) {
            // Atualiza os dados do cliente
            $customer->update($customerData);

            // Atualiza ou cria endereços, se houver
            if ($request->has('addresses')) {
                foreach ($request->input('addresses') as $addressData) {
                    if (isset($addressData['id'])) {
                        // Remove o ID antes de atualizar
                        $id = $addressData['id'];
                        unset($addressData['id']);
                        $customer->deliveryAddresses()->where('id', $id)->update($addressData);
                    } else {
                        // Cria novo endereço
                        $customer->deliveryAddresses()->create($addressData);
                    }
                }
            }
        });

        return response()->json($customer->load('deliveryAddresses'), 200);
    }



    /**
     * @OA\Delete(
     *     path="/customers/{id}",
     *     summary="Excluir um cliente",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Cliente excluído")
     * )
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(null, 204);
    }


    /**
     * @OA\Get(
     *     path="/customers/search",
     *     summary="Pesquisar clientes por nome ou telefone",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="phone", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Resultados da pesquisa")
     * )
     */
    public function SearchCustomer(Request $request)
    {
        $user = $request->user();

        // Verifica se o campo 'name' ou 'phone' foi passado na requisição
        $query = Customer::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('phone')) {
            $query->orWhere('phone', 'like', '%' . $request->phone . '%');
        }

        return $query->with('deliveryAddresses')
        ->where('company_id', $user->company_id)->get();
    }
}
