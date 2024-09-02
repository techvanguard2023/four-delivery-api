<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'company_id' => 1,
            'customer_id' => 1,
            'total_price' => 300,
            'status_id' => 1,
            'payment_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Order::create([
            'company_id' => 1,
            'customer_id' => 2,
            'total_price' => 700,
            'status_id' => 2,
            'payment_status' => 'paid',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Order::create([
            'company_id' => 1,
            'customer_id' => 3,
            'total_price' => 500,
            'status_id' => 3,
            'payment_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
