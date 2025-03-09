<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissions = [
            'Administrador' => [
                'manage-users',
                'manage-settings',
                'manage-permissions',
                'approve-requests',
                'view-reports',
                'manage-orders',
                'attend-customers',
                'place-orders',
                'update-order-status',
                'view-assigned-orders',
                'update-delivery-status',
            ],
            'Gerente' => [
                'approve-requests',
                'view-reports',
                'manage-orders',
                'attend-customers',
            ],
            'Atendente' => [
                'manage-orders',
                'attend-customers',
            ],
            'Garçom' => [
                'place-orders',
                'update-order-status',
            ],
            'Entregador' => [
                'view-assigned-orders',
                'update-delivery-status',
            ],
        ];

        $timestamp = now();

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissionIds = Permission::whereIn('tag', $permissions)->pluck('id')->toArray();

                $data = array_map(fn($permissionId) => [
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'created_at' => $timestamp,
                ], $permissionIds);

                DB::table('role_permissions')->insert($data);
            }
        }
    }
}
