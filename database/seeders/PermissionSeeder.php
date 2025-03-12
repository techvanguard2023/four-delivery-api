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
            ['name' => 'Visualizar Relatórios', 'description' => 'Permite acessar relatórios detalhados do sistema.', 'tag' => 'view-reports'],
            ['name' => 'Gerenciar Pedidos', 'description' => 'Permite criar, atualizar e cancelar pedidos.', 'tag' => 'manage-orders'],
            ['name' => 'Gerenciar Clientes', 'description' => 'Permite gerenciar informações dos clientes.', 'tag' => 'manage-customer'],
            ['name' => 'Gerenciar Entregadores', 'description' => 'Permite gerenciar os entregadores do sistema.', 'tag' => 'manage-delivery-person'],
            ['name' => 'Gerenciar Produtos', 'description' => 'Permite adicionar, editar e remover produtos.', 'tag' => 'manage-products'],
            ['name' => 'Gerenciar Estoque', 'description' => 'Permite controlar o estoque de produtos.', 'tag' => 'manage-stock'],
            ['name' => 'Gerenciar Finanças', 'description' => 'Permite visualizar e gerenciar informações financeiras.', 'tag' => 'manage-finance'],
            ['name' => 'Gerenciar Usuários', 'description' => 'Permite criar, editar e remover usuários do sistema.', 'tag' => 'manage-users'],
            ['name' => 'Gerenciar Assinaturas', 'description' => 'Permite gerenciar planos de assinatura.', 'tag' => 'manage-subscription'],
            ['name' => 'Gerenciar Configurações', 'description' => 'Permite alterar as configurações do sistema.', 'tag' => 'manage-settings'],
            ['name' => 'Aprovar Solicitações', 'description' => 'Permite aprovar ou rejeitar solicitações feitas pelos usuários.', 'tag' => 'approve-requests'],
            ['name' => 'Atender Clientes', 'description' => 'Permite interagir com clientes e responder suas solicitações.', 'tag' => 'attend-customers'],
            ['name' => 'Registrar Pedidos', 'description' => 'Permite adicionar pedidos ao sistema.', 'tag' => 'place-orders'],
            ['name' => 'Atualizar Status do Pedido', 'description' => 'Permite mudar o status do pedido conforme necessário.', 'tag' => 'update-order-status'],
            ['name' => 'Visualizar Pedidos Atribuídos', 'description' => 'Permite ver a lista de pedidos que deve entregar.', 'tag' => 'view-assigned-orders'],
            ['name' => 'Atualizar Status de Entrega', 'description' => 'Permite marcar pedidos como entregues.', 'tag' => 'update-delivery-status'],
            ['name' => 'Gerenciar Permissões', 'description' => 'Permite gerenciar as permissões de usuários e funções.', 'tag' => 'manage-permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['tag' => $permission['tag']], $permission);
        }
    }
}
