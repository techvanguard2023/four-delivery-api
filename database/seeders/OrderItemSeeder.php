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
            'price' => 4.50,
            'observation' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 1,
            'item_id' => 2,
            'quantity' => 1,
            'price' => 3.50,
            'observation' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 3,
            'quantity' => 1,
            'price' => 3.50,
            'observation' => 'Gelo e limão',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 4,
            'quantity' => 1,
            'price' => 2.50,
            'observation' => 'Com gelo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 3,
            'item_id' => 5,
            'quantity' => 1,
            'price' => 3.50,
            'observation' => 'Com gelo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 4,
            'item_id' => 1,
            'quantity' => 1,
            'price' => 4.50,
            'observation' => 'Sem gelo',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
