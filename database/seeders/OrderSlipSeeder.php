<?php

namespace Database\Seeders;

use App\Models\OrderSlip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSlipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderSlip::create([
            'company_id' => 1,
            'customer_name' => 'Ricardo',
            'total_price' => 130.00,
            'status_id' => 1,
            'payment_status' => 'pending',
            'order_type_id' => 1,
            'order_origin_id' => 6,
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        OrderSlip::create([
            'company_id' => 2,
            'customer_name' => 'Sarah',
            'total_price' => 30.00,
            'status_id' => 1,
            'payment_status' => 'pending',
            'order_type_id' => 1,
            'order_origin_id' => 6,
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        OrderSlip::create([
            'company_id' => 3,
            'customer_name' => 'Zé',
            'total_price' => 150.00,
            'status_id' => 1,
            'payment_status' => 'pending',
            'order_type_id' => 1,
            'order_origin_id' => 6,
            'position' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
