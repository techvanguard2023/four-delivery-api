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
                'start_week_day' => 'Quarta-feira',
                'end_week_day' => 'Domingo',
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
                'reservation_cancellation_tolerance' => 5,
                'reservation_cancellation_tolerance_type' => 'minutes',
                'menu_address' => 'https://fourdelivery.com.br/digital-menu/',
                'has_couvert_tax' => true,
                'couvert_tax' => '10.00'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('settings')->insert([
            'company_id' => 2,
            'data' => json_encode([
                'start_week_day' => 'Quarta-feira',
                'end_week_day' => 'Sábado',
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
                'reservation_cancellation_tolerance' => 5,
                'reservation_cancellation_tolerance_type' => 'minutes',
                'menu_address' => 'https://fourdelivery.com.br/digital-menu/',
                'has_couvert_tax' => true,
                'couvert_tax' => '10.00'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('settings')->insert([
            'company_id' => 3,
            'data' => json_encode([
                'start_week_day' => 'Quarta-feira',
                'end_week_day' => 'Domingo',
                'start_time' => '18:00:00',
                'end_time' => '23:59:59',
                'alternative_start_week_day' => 'Sábado',
                'alternative_end_week_day' => 'Domingo',
                'alternative_start_time' => '23:59:59',
                'alternative_end_time' => '00:00:00',
                'total_tables' => 20,
                'stock_alert' => 20,
                'stock_danger' => 10,
                'stock_critical' => 5,
                'reservation_cancellation_tolerance' => 5,
                'reservation_cancellation_tolerance_type' => 'minutes',
                'menu_address' => 'https://fourdelivery.com.br/digital-menu/',
                'has_couvert_tax' => true,
                'couvert_tax' => '10.00'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}