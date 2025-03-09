<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Administrador
            [
                'name' => 'Gerenciar Usuários',
                'description' => 'Permite criar, editar e remover usuários do sistema.',
                'tag' => 'manage-users'
            ],
            [
                'name' => 'Gerenciar Configurações',
                'description' => 'Permite alterar as configurações do sistema.',
                'tag' => 'manage-settings'
            ],
            [
                'name' => 'Gerenciar Permissões',
                'description' => 'Permite gerenciar as permissões de usuários e funções.',
                'tag' => 'manage-permissions'
            ],

            // Gerente
            [
                'name' => 'Aprovar Solicitações',
                'description' => 'Permite aprovar ou rejeitar solicitações feitas pelos usuários.',
                'tag' => 'approve-requests'
            ],
            [
                'name' => 'Visualizar Relatórios',
                'description' => 'Permite acessar relatórios detalhados do sistema.',
                'tag' => 'view-reports'
            ],

            // Atendente
            [
                'name' => 'Gerenciar Pedidos',
                'description' => 'Permite criar, atualizar e cancelar pedidos.',
                'tag' => 'manage-orders'
            ],
            [
                'name' => 'Atender Clientes',
                'description' => 'Permite interagir com clientes e responder suas solicitações.',
                'tag' => 'attend-customers'
            ],

            // Garçom
            [
                'name' => 'Registrar Pedidos',
                'description' => 'Permite adicionar pedidos ao sistema.',
                'tag' => 'place-orders'
            ],
            [
                'name' => 'Atualizar Status do Pedido',
                'description' => 'Permite mudar o status do pedido conforme necessário.',
                'tag' => 'update-order-status'
            ],

            // Entregador
            [
                'name' => 'Visualizar Pedidos Atribuídos',
                'description' => 'Permite ver a lista de pedidos que deve entregar.',
                'tag' => 'view-assigned-orders'
            ],
            [
                'name' => 'Atualizar Status de Entrega',
                'description' => 'Permite marcar pedidos como entregues.',
                'tag' => 'update-delivery-status'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
