<?php

namespace Database\Seeders;

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
        $itemsCount = DB::table('items')->count(); // Obtém o total de itens cadastrados

        $stocks = [];
        for ($i = 1; $i <= $itemsCount; $i++) {
            $stocks[] = [
                'item_id' => $i,
                'quantity' => rand(5, 100), // Define uma quantidade aleatória entre 5 e 100
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Stock::insert($stocks); // Insere todos os registros de uma vez
    }
}
