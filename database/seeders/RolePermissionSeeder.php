<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupera todos os IDs de permissões disponíveis
        $availablePermissionIds = Permission::pluck('id')->toArray();

        $rolePermissions = [
            // Admin
            [
                'role_id' => 1,
                'permission_id' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                'created_at' => now(),
            ],
            // Gerente
            [
                'role_id' => 2,
                'permission_id' => [4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                'created_at' => now()
            ],
            // atendente
            [
                'role_id' => 3,
                'permission_id' => [10, 11, 13],
                'created_at' => now(),
            ],

        ];

        foreach ($rolePermissions as $rolePermission) {
            $roleId = $rolePermission['role_id'];
            $permissionIds = $rolePermission['permission_id'];
            $createdAt = $rolePermission['created_at'];

            // Verifica se cada permission_id fornecido existe nas permissões disponíveis
            foreach ($permissionIds as $permissionId) {
                if (!in_array($permissionId, $availablePermissionIds)) {
                    Log::error("Permissão inválida: A permissão com ID {$permissionId} não existe.");
                    // Se desejar, você pode lançar uma exceção aqui para interromper a execução
                    throw new \Exception("Permissão inválida: A permissão com ID {$permissionId} não existe.");
                }
            }

            $dataToInsert = array_map(function ($permissionId) use ($roleId, $createdAt) {
                return [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $createdAt,
                    'updated_at' => now()
                ];
            }, $permissionIds);

            DB::table('role_permissions')->insert($dataToInsert);
        }
    }
}
