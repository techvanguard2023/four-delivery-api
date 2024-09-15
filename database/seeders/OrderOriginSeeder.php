<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderOrigin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        OrderOrigin::create([
            'name' => 'Aplicativo',
            'description' => 'Para pedidos feitos pelo aplicativo'
        ]);

        OrderOrigin::create([
            'name' => 'Site',
            'description' => 'Para pedidos feitos pelo site'
        ]);

        OrderOrigin::create([
            'name' => 'Telefone',
            'description' => 'para pedidos feitos por telefone'
        ]);

        OrderOrigin::create([
            'name' => 'Pessoalmente',
            'description' => 'Para pedidos feitos pessoalmente'
        ]);

        OrderOrigin::create([
            'name' => 'E-mail',
            'description' => 'Para pedidos feitos por e-mail'
        ]);

        OrderOrigin::create([
            'name' => 'WhatsApp',
            'description' => 'Para pedidos feitos por WhatsApp'
        ]);
    }
}
