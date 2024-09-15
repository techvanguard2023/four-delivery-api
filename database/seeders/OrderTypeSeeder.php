<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderTypes = [
            ['name' => 'Entrega', 'description' => 'Para pedidos com entrega'],
            ['name' => 'Retirada', 'description' => 'Para pedidos para levar'],
            ['name' => 'Consumo no local', 'description' => 'Para pedidos para consumo no local'],
        ];

        foreach ($orderTypes as $orderType) {
            \App\Models\OrderType::create($orderType);
        }
    }
}
