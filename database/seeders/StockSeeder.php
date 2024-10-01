<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stock::create([
            'item_id' => 1,
            'quantity' => 18,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Stock::create([
            'item_id' => 2,
            'quantity' => 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Stock::create([
            'item_id' => 3,
            'quantity' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Stock::create([
            'item_id' => 4,
            'quantity' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Stock::create([
            'item_id' => 5,
            'quantity' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
