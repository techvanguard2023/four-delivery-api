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
                'manage-customer',
                'manage-delivery-person',
                'manage-products',
                'manage-stock',
                'manage-finance',
                'manage-users',
                'manage-subscription',
                'manage-settings',
                'approve-requests',
                'attend-customers',
                'place-orders',
                'update-order-status',
                'view-assigned-orders',
                'update-delivery-status',
                'manage-permissions',
            ],
            'Gerente' => [
                'view-reports',
                'manage-orders',
                'manage-customer',
                'manage-delivery-person',
                'manage-products',
                'manage-stock',
                'manage-finance',
                'manage-users',
                'manage-subscription',
                'manage-settings',
                'approve-requests',
                'attend-customers'
            ],
            'Atendente' => [
                'manage-orders',
                'manage-customer',
                'manage-delivery-person',
                'attend-customers',
                'approve-requests',
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
