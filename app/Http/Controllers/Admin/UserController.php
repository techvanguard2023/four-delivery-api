<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        // Obter o usuário autenticado
        $user = auth()->user();

        // Carregar roles e permissões do usuário
        $user->load('roles.permissions');

        // Verificar se o usuário tem uma role id igual a 1
        $isAdmin = $user->roles->contains('id', 1);

        if (!$isAdmin) {
            if ($user->company_id) {
                // Se não for admin, apenas liste os usuários com o mesmo company_id
                $users = User::with('company', 'ticket')
                    ->where('company_id', $user->company_id)
                    ->paginate(25);
            } else {
                abort(400, 'Company ID is required for non-admin users.');
            }
        } else {
            // Se for admin, carregue todos os usuários
            $users = User::with('company', 'ticket')->paginate(25);
        }

        if ($users->isEmpty()) {
            return response()->json(['message' => 'Nenhum usuário encontrado.'], 404);
        }

        return UserResource::collection($users);
    }



    public function show(User $user)
    {
        $user = User::with('company')->find($user->id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return new UserResource($user);
    }

    public function showLoggedUser(Request $request)
    {
        $user = $request->user();
        // Carregar a role e as permissões do usuário
        $user->load('roles.permissions');
        return new UserResource($user);
    }


    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'company_id' => ['required'],
            'role_id' => ['required']
        ]);

        // Crie o usuário
        $user = User::create([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
            'company_id' => $input['company_id']
        ]);

        $user->roles()->attach($input['role_id']);

        return response()->json(['message' => 'User created successfully.'], 201);
    }


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
