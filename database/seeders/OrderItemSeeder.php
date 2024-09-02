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
            'price' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 1,
            'item_id' => 2,
            'quantity' => 1,
            'price' => 200,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 3,
            'quantity' => 1,
            'price' => 300,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 2,
            'item_id' => 4,
            'quantity' => 1,
            'price' => 400,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderItem::create([
            'order_id' => 3,
            'item_id' => 5,
            'quantity' => 1,
            'price' => 500,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
