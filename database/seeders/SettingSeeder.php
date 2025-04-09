<?php

namespace Database\Seeders;

use App\Models\Setting;
use Hamcrest\Core\Set;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'company_id' => 1,
            'data' => json_encode([
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('settings')->insert([
            'company_id' => 2,
            'data' => json_encode([
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('settings')->insert([
            'company_id' => 3,
            'data' => json_encode([
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}