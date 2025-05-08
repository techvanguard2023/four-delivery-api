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
                'view-reports',
                'manage-orders',
                'manage-orders-store',
                'manage-customers',
                'manage-delivery-person',
                'manage-products',
                'manage-stocks',
                'manage-finances',
                'manage-users',
                'manage-subscription',
                'manage-settings',
                'manage-permissions',
                'manage-reservations',
                'manager-counter-order'
            ],
            'Gerente' => [
                'view-reports',
                'manage-orders',
                'manage-orders-store',
                'manage-customers',
                'manage-delivery-person',
                'manage-products',
                'manage-stocks',
                'manage-finances',
                'manage-users',
                'manage-subscription',
                'manage-settings',
                'manage-permissions',
                'manage-reservations',
                'manager-counter-order'
            ],
            'Atendente' => [
                'manage-orders',
                'manage-orders-store',
                'manage-customers',
                'manage-delivery-person',
                'manage-reservations',
                'manager-counter-order'
            ],
            'Garçom' => [
                'manage-orders-store',
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
