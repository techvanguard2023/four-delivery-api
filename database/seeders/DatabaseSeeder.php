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
    private static $password;

    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(PlanFeatureSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(CompanyPlanSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(PermissionSeeder::class);


        User::create([
            'name' => 'Robson Gomes Pedreira',
            'email' => 'masterdba6@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Gerente',
            'email' => 'gerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Atendente',
            'email' => 'atendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Emporio dos Sabores Gerente',
            'email' => 'emporiodosaborgerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        User::create([
            'name' => 'Emporio dos Sabores Atendente',
            'email' => 'emporiodosaboratendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        $this->call(OrderOriginSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(OrderTypeSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(DeliveryPeopleSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderItemSeeder::class);
        $this->call(DeliveryAddressSeeder::class);
    }
}
