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
        $stocks = [];

        for ($i = 1; $i <= 50; $i++) {
            $stocks[] = [
                'item_id' => $i,
                'quantity' => mt_rand(10, 100),
            ];
        }

        DB::table('stocks')->insert($stocks);
    }
}
