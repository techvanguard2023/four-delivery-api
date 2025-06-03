<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(PlanFeatureSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(CompanyPlanSeeder::class);
        $this->call(DeliveryLocationSeeder::class);
        $this->call(CategorySeeder::class);
        // $this->call(ItemsSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OrderOriginSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(OrderTypeSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserRoleSeeder::class);
        // $this->call(CustomerSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(DeliveryPeopleSeeder::class);
        // $this->call(OrderSeeder::class);
        // $this->call(OrderSlipSeeder::class);
        // $this->call(OrderItemSeeder::class);
        // $this->call(DeliveryAddressSeeder::class);
        $this->call(SettingSeeder::class);
        
    }
}
