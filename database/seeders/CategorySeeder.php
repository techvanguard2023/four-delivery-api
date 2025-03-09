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
            'name' => 'Bebidas Frias',
            'description' => 'Refrigerantes, cerveja, sucos, água mineral, chá gelado, bebidas energéticas, água de coco.',
            'image_url' => 'https://centraldebebidas.com.br/wp-content/uploads/2020/05/refrigerantes.jpg'
        ]);

        Category::create([
            'name' => 'Bebidas Quentes',
            'description' => 'Café, cappuccino, chá, chocolate quente.',
            'image_url' => 'https://anamariareceitas.com.br/wp-content/uploads/2023/03/Quentao-de-chocolate-e-biscoitinhos-de-limao.jpg'
        ]);

        Category::create([
            'name' => 'Sanduíches',
            'description' => 'Hambúrgueres, cheeseburgers, sanduíches naturais, wraps.',
            'image_url' => 'https://lirp.cdn-website.com/33406c6e/dms3rep/multi/opt/Afinal-como-precificar-cardapio-Nos-te-explicamos-2048x1489-1-640w.jpg'
        ]);

        Category::create([
            'name' => 'Salgados',
            'description' => 'Coxinha, empada, quibe, esfiha, pastel, croissant, pão de queijo.',
            'image_url' => 'https://interlasermaquinas.com.br/wp-content/uploads/2022/12/salgados-para-festa-1024x686.jpg'
        ]);

        Category::create([
            'name' => 'Sobremesas',
            'description' => 'Tortas, bolos, pudins, brownies, cookies, sorvetes.',
            'image_url' => 'https://static.wixstatic.com/media/8024f8_0fec7d7993e4404a81fcebf7a6840fb0~mv2.png/v1/fill/w_640,h_640,al_c,q_90,usm_0.66_1.00_0.01,enc_auto/8024f8_0fec7d7993e4404a81fcebf7a6840fb0~mv2.png'
        ]);

        Category::create([
            'name' => 'Açaí e Smoothies',
            'description' => 'Açaí na tigela, smoothies de frutas, milkshakes.',
            'image_url' => 'https://lifemadesweeter.com/wp-content/uploads/Berry-Acai-Smoothie-Bowl-recipe-vegan-healthy-gluten-free-low-paleo-Whole30-500x500.jpg'
        ]);

        Category::create([
            'name' => 'Saladas',
            'description' => 'Salada de frutas, salada verde, salada de frango, salada caprese.',
            'image_url' => 'https://www.academiaassai.com.br/sites/default/files/styles/noticia_1020x640/public/shutterstock_318379043.jpg'
        ]);

        Category::create([
            'name' => 'Aperitivos e Petiscos',
            'description' => 'Batata frita, onion rings, nuggets, nachos, mini pastéis.',
            'image_url' => 'https://www.segs.com.br/media/k2/items/cache/7f76895795a93da2100c1a26609c8a94_XL.jpg'
        ]);

        Category::create([
            'name' => 'Pratos Rápidos',
            'description' => 'Pratos executivos, strogonoff, filé de frango, massas.',
            'image_url' => 'https://s2-receitas.glbimg.com/NLyU78Jaoyf6mNBJyEm8PcvGwgU=/540x304/middle/smart/https://i.s3.glbimg.com/v1/AUTH_1f540e0b94d8437dbbc39d567a1dee68/internal_photos/bs/2022/8/O/xH9h1GSnW1LhMobyL7hQ/strogonoff-de-frango-receita.jpg'
        ]);

        Category::create([
            'name' => 'Vegetarianas/Veganas',
            'description' => 'Hambúrguer vegetariano, sanduíches veganos, saladas veganas.',
            'image_url' => 'https://i0.wp.com/www.vidanatural.org.br/wp-content/uploads/2020/02/0_dieta-vegetariana.jpg'
        ]);

        Category::create([
            'name' => 'Pizzas e Focaccias',
            'description' => 'Mini pizzas, focaccia, fatias de pizza.',
            'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRy9xktQuGNfoagNTxvObj3DsZDHWyBfUUz9Q&s'
        ]);

        Category::create([
            'name' => 'Doces e Guloseimas',
            'description' => 'Brigadeiro, beijinho, trufas, gelatinas, pipoca doce.',
            'image_url' => 'https://st4.depositphotos.com/14670260/20079/i/1600/depositphotos_200798038-stock-photo-candies-jelly-sugar-colorful-array.jpg'
        ]);

        Category::create([
            'name' => 'Pães e Torradas',
            'description' => 'Pão francês, torradas, baguetes, pão de forma.',
            'image_url' => 'https://lh6.googleusercontent.com/proxy/8zBkBdhXdleQdBnx9Q8lys54l5F9fvP83JLEILjvHFEBgMpekIlJDGPF2oSz4vCZUJjvSpmEA_mBhc8abPmkP_M8wANkJaILRyG1I1qtMafRyvY4hlPjjI3EAI3_Jq7-8bbdqRzw'
        ]);

        Category::create([
            'name' => 'Acompanhamentos',
            'description' => 'Ketchup, maionese, mostarda, molhos especiais, maionese temperada.',
            'image_url' => 'https://oimparcial.com.br/app/uploads/2019/10/molhos_para_carne.jpg'
        ]);

        Category::create([
            'name' => 'Sucos Naturais',
            'description' => 'Sucos de laranja, limão, maracujá, detox, sucos de polpa.',
            'image_url' => 'https://www.academiaassai.com.br/sites/default/files/styles/noticia_1020x640/public/sucos_naturais_capa.jpg?itok=MThhZZOC'
        ]);

        Category::create([
            'name' => 'Combo de Refeições',
            'description' => 'Combos de hambúrguer + bebida + batata frita, combos de sanduíches.',
            'image_url' => 'https://static.itdg.com.br/images/auto-auto/f15a9b098a44a81d78f8129e1a11f3b1/como-organizar-a-alimentacao-da-semana.jpg'
        ]);

        Category::create([
            'name' => 'Especiais do Dia',
            'description' => 'Itens ou pratos que variam diariamente.',
            'image_url' => 'https://receitinhas.com.br/wp-content/uploads/2023/05/pratos-especiais-para-almoco-de-dia-das-maes-1.jpg'
        ]);

        Category::create([
            'name' => 'Kids',
            'description' => 'Mini-hambúrguer, batata frita, nuggets, sucos em caixinha.',
            'image_url' => 'https://media-cdn.tripadvisor.com/media/photo-s/0d/e4/82/45/prato-kids-loucos-por.jpg'
        ]);

        Category::create([
            'name' => 'Caldos e Sopas',
            'description' => 'Caldo de feijão, sopa de legumes, caldo verde, canja de galinha etc..',
            'image_url' => 'https://conteudo.solutudo.com.br/wp-content/uploads/2022/05/Lugares-que-vendem-caldos-em-Presidente-Prudente.jpg'
        ]);
    }
}
