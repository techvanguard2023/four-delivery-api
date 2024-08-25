<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserRoleSeeder::class);
    }
}
