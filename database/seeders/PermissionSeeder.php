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

        Permission::create([
            'id' => 1,
            'name' => 'Gerenciar Empresas',
            'description' => 'Permite gerenciar as empresas do sistema.',
            'tag' => 'manage-companies'
        ]);

        Permission::create([
            'id' => 2,
            'name' => 'Gerenciar Roles',
            'description' => 'Permite gerenciar as Roles do sistema.',
            'tag' => 'manage-roles'
        ]);

        Permission::create([
            'id' => 3,
            'name' => 'Gerenciar Permissões',
            'description' => 'Permite gerenciar as Permissões do sistema.',
            'tag' => 'manage-permissions'
        ]);

        Permission::create([
            'id' => 4,
            'name' => 'Gerenciar Categorias',
            'description' => 'Permite gerenciar as categorias do sistema.',
            'tag' => 'manage-categories'
        ]);

        Permission::create([
            'id' => 5,
            'name' => 'Gerenciar Produtos',
            'description' => 'Permite gerenciar os produtos do sistema.',
            'tag' => 'manage-products'
        ]);

        Permission::create([
            'id' => 6,
            'name' => 'Acesso ao Dashbord',
            'description' => 'Permite ver o dashbord de vendas.',
            'tag' => 'view-sales-reports'
        ]);

        Permission::create([
            'id' => 7,
            'name' => 'Gerenciar Usuários',
            'description' => 'Permite gerenciar os usuários do sistema.',
            'tag' => 'manage-users'
        ]);

        Permission::create([
            'id' => 8,
            'name' => 'Gerenciar Clientes',
            'description' => 'Permite gerenciar os clientes do sistema.',
            'tag' => 'manage-customers'
        ]);

        Permission::create([
            'id' => 9,
            'name' => 'Gerenciar Entregadores',
            'description' => 'Permite gerenciar os entregadores do sistema.',
            'tag' => 'manage-delivery-person'
        ]);

        Permission::create([
            'id' => 10,
            'name' => 'Gerenciar pedidos',
            'description' => 'Permite gerenciar os pedidos do sistema.',
            'tag' => 'manage-orders'
        ]);

        Permission::create([
            'id' => 11,
            'name' => 'Atualizar status dos pedidos',
            'description' => 'Permite alterar o status dos pedidos do sistema',
            'tag' => 'update-order-status'
        ]);

        Permission::create([
            'id' => 12,
            'name' => 'Gerenciar Configurações',
            'description' => 'Permite gerenciar as configurações do sistema.',
            'tag' => 'manage-settings'
        ]);

        Permission::create([
            'id' => 13,
            'name' => 'Permissão Básica Padrão',
            'description' => 'Permite o mínimo possível no sistema.',
            'tag' => 'default'
        ]);
    }
}
