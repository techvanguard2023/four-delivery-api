<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'company_id' => 1,
            'name' => 'Bebidas Frias',
            'description' => 'Refrigerantes, sucos, água mineral, chá gelado, bebidas energéticas, água de coco.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Bebidas Quentes',
            'description' => 'Café, cappuccino, chá, chocolate quente.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Sanduíches',
            'description' => 'Hambúrgueres, cheeseburgers, sanduíches naturais, wraps.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Salgados',
            'description' => 'Coxinha, empada, quibe, esfiha, pastel, croissant, pão de queijo.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Sobremesas',
            'description' => 'Tortas, bolos, pudins, brownies, cookies, sorvetes.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Açaí e Smoothies',
            'description' => 'Açaí na tigela, smoothies de frutas, milkshakes.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Saladas',
            'description' => 'Salada de frutas, salada verde, salada de frango, salada caprese.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Aperitivos e Petiscos',
            'description' => 'Batata frita, onion rings, nuggets, nachos, mini pastéis.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Pratos Rápidos',
            'description' => 'Pratos executivos, strogonoff, filé de frango, massas.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Opções Vegetarianas/Veganas',
            'description' => 'Hambúrguer vegetariano, sanduíches veganos, saladas veganas.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Pizzas e Focaccias',
            'description' => 'Mini pizzas, focaccia, fatias de pizza.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Doces e Guloseimas',
            'description' => 'Brigadeiro, beijinho, trufas, gelatinas, pipoca doce.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Pães e Torradas',
            'description' => 'Pão francês, torradas, baguetes, pão de forma.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Molhos e Acompanhamentos',
            'description' => 'Ketchup, maionese, mostarda, molhos especiais, maionese temperada.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Sucos Naturais',
            'description' => 'Sucos de laranja, limão, maracujá, detox, sucos de polpa.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Combo de Refeições',
            'description' => 'Combos de hambúrguer + bebida + batata frita, combos de sanduíches.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Especiais do Dia',
            'description' => 'Itens ou pratos que variam diariamente.'
        ]);

        Category::create([
            'company_id' => 1,
            'name' => 'Kids',
            'description' => 'Mini-hambúrguer, batata frita, nuggets, sucos em caixinha.'
        ]);
    }
}
