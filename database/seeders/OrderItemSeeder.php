<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::create([
            'order_id' => 1,
            'item_id' => 1,
            'quantity' => 1,
            'price' => 77.00,
            'observation' => 'Sem cebola',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 1,
            'item_id' => 2,
            'quantity' => 1,
            'price' => 53.00,
            'observation' => 'Bem gelado',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 3,
            'quantity' => 1,
            'price' => 71.00,
            'observation' => 'Sem azeitona',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 4,
            'quantity' => 1,
            'price' => 61.00,
            'observation' => 'Com muito bacon',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 3,
            'item_id' => 5,
            'quantity' => 1,
            'price' => 63.00,
            'observation' => 'Sem cheiro verde',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 4,
            'item_id' => 9,
            'quantity' => 1,
            'price' => 91.00,
            'observation' => 'Sem cebola',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
