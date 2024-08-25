<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Obter o usuário autenticado
        $user = auth()->user();

        // Carregar roles e permissões do usuário
        $user->load('roles.permissions');

        // Verificar se o usuário tem uma role id igual a 1 (Administrador)
        $isAdmin = $user->roles->contains('id', 1);

        $roles = Role::query();

        if (!$isAdmin) {
            // Se não for administrador, filtrar para não incluir a role de administrador
            $roles->where('id', '!=', 1);
        }

        // Retornar os roles paginados
        return $roles->paginate(25);
    }
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'permission_ids' => 'required|array',
        ]);

        $role = Role::create([
            'name' => $input['name'],
            'description' => $input['description'],
            'created_at' => now(),
        ]);

        $role->permissions()->attach($input['permission_ids']);

        return response()->json(['message' => 'Role created successfully.'], 200);
    }

    public function show($id)
    {
        $role = DB::table('roles AS r')
            ->select(
                'r.id AS role_id',
                'r.name AS role_name',
                'r.description AS role_description',
                'r.created_at as created_at',
                'p.id AS permission_id',
                'p.name AS permission_name'
            )
            ->leftJoin('role_permissions AS rp', 'r.id', '=', 'rp.role_id')
            ->leftJoin('permissions AS p', 'rp.permission_id', '=', 'p.id')
            ->where('r.id', $id)
            ->get();


        $groupedPermissions = $role->groupBy('role_id')->map(function ($role) {
            // Mapear os resultados e formatar os dados da role e suas permissões
            return [
                'id' => $role->first()->role_id,
                'name' => $role->first()->role_name,
                'description' => $role->first()->role_description,
                'created_at' => $role->first()->created_at,
                'permissions' => $role->map(function ($permission) {
                    return [
                        'id' => $permission->permission_id,
                        'name' => $permission->permission_name,
                    ];
                }),
            ];
        })->values()->first();

        return response()->json($groupedPermissions);
    }

    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'permission_ids' => 'required|array',
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $input['name'],
            'description' => $input['description'],
        ]);

        // Sincronizar as permissões associadas à role
        $role->permissions()->sync($input['permission_ids']);

        return response()->json(['message' => 'Role updated successfully.'], 200);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Remover as permissões associadas à role
        $role->permissions()->detach();

        // Excluir a role
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.'], 200);
    }
}
