<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrador', 'description' => 'Este papel geralmente tem acesso total ao sistema e pode fazer alterações em qualquer parte do sistema, incluindo configurações, usuários e permissões.'],
            ['name' => 'Gerente', 'description' => 'Este papel pode ter permissões adicionais além do usuário padrão, como aprovação de solicitações, acesso a relatórios avançados ou recursos de gestão de equipe.'],
            ['name' => 'Atendente', 'description' => 'Este papel é atribuído a usuários regulares do sistema. Eles têm acesso limitado, geralmente apenas para visualização e interação com recursos específicos do sistema.'],
            ['name' => 'Garçom', 'description' => 'Eles têm acesso limitado a fazer pedidos.'],
            ['name' => 'Entregador', 'description' => 'Eles têm acesso limitado à lista de pedidos associados a ele.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
