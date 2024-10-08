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
            'delivery_person_id' => 1,
            'total_price' => 130.00,
            'status_id' => 1,
            'payment_status' => 'pending',
            'order_type_id' => 1,
            'order_origin_id' => 6,
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Order::create([
            'company_id' => 1,
            'customer_id' => 2,
            'total_price' => 132.00,
            'status_id' => 2,
            'payment_status' => 'paid',
            'order_type_id' => 2,
            'order_origin_id' => 1,
            'position' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Order::create([
            'company_id' => 1,
            'customer_id' => 3,
            'total_price' => 63.00,
            'status_id' => 3,
            'payment_status' => 'pending',
            'order_type_id' => 3,
            'order_origin_id' => 4,
            'position' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Order::create([
            'company_id' => 1,
            'total_price' => 91.00,
            'status_id' => 3,
            'payment_status' => 'pending',
            'order_type_id' => 3,
            'order_origin_id' => 4,
            'position' => 4,
            'created_at' => now(),
            'updated_at' => now(),
            'location' => 'Mesa 1'
        ]);
    }
}
