<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;

use App\Services\UserRoleService;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Listar todos os usuários do sistema",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de usuários")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user);

        if ($roleId == 1) {
            return UserResource::collection(
                User::with(['company.companyPlans.plan.features', 'roles.permissions'])->get()
            );
        } else {
            return UserResource::collection(
                User::where('company_id', $user->company_id)
                    ->with(['company.companyPlans.plan.features', 'roles.permissions'])
                    ->get()
            );
        }
    }


    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Mostrar detalhes de um usuário",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalhes do usuário"),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */
    public function show(User $user)
    {
        $user = User::with('company')->find($user->id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Mostrar dados do usuário logado",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Dados do usuário logado")
     * )
     */
    public function showLoggedUser(Request $request)
    {
        $user = $request->user();
        // Carregar a role e as permissões do usuário
        $user->load('roles.permissions');
        return new UserResource($user);
    }


    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Criar novo usuário no sistema",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","phone","password","password_confirmation","role_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password"),
     *             @OA\Property(property="role_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário criado")
     * )
     */
    public function store(Request $request)
    {

        $authenticatedUser = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], // 'confirmed' cuida da confirmação
            'role_id' => ['required', 'exists:roles,id'],
        ]);
        


        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'company_id' => $authenticatedUser->company_id,
        ]);

        $user->roles()->attach($validated['role_id']);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => new UserResource($user),
        ], 201);
        
    }



    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Atualizar um usuário",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Usuário atualizado")
     * )
     */
    public function update(Request $request, User $user)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'change_password' => ['boolean'],
            'password' => ['required_if:change_password,true', 'string', 'max:255'],
            'change_company_id' => ['boolean'],
            'company_id' => ['required_if:change_company_id,true'],
            'change_role_id' => ['boolean'],
            'role_id' => ['required_if:change_role_id,true'],
            'change_status' => ['boolean'],
            'status' => ['required_if:change_status,true', 'max:10'],
        ]);

        // Remove o campo 'password' se 'change_password' não for verdadeiro
        if (empty($input['change_password'])) {
            unset($input['password']);
        }

        if (empty($input['change_role_id'])) {
            unset($input['role_id']);
        }

        if (empty($input['change_company_id'])) {
            unset($input['company_id']);
        }

        if (empty($input['change_status'])) {
            unset($input['status']);
        }

        $user->update($input);

        return response()->json(['message' => 'User updated successfully.'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Excluir um usuário",
     *     tags={"Usuários"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Usuário excluído")
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
