<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function generateCnpj()
    {
        $n1 = mt_rand(0, 9);
        $n2 = mt_rand(0, 9);
        $n3 = mt_rand(0, 9);
        $n4 = mt_rand(0, 9);
        $n5 = mt_rand(0, 9);
        $n6 = mt_rand(0, 9);
        $n7 = mt_rand(0, 9);
        $n8 = mt_rand(0, 9);
        $n9 = mt_rand(0, 9);
        $n10 = mt_rand(0, 9);
        $n11 = mt_rand(0, 9);
        $n12 = mt_rand(0, 9);

        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        return sprintf('%d%d%d%d%d%d%d%d%d%d%d%d%d%d', $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $n10, $n11, $n12, $d1, $d2);
    }

    public function generateCpf()
    {
        $n1 = mt_rand(0, 9);
        $n2 = mt_rand(0, 9);
        $n3 = mt_rand(0, 9);
        $n4 = mt_rand(0, 9);
        $n5 = mt_rand(0, 9);
        $n6 = mt_rand(0, 9);
        $n7 = mt_rand(0, 9);
        $n8 = mt_rand(0, 9);
        $n9 = mt_rand(0, 9);
        $n10 = mt_rand(0, 9);
        $n11 = mt_rand(0, 9);

        $d1 = $n11 * 2 + $n10 * 3 + $n9 * 4 + $n8 * 5 + $n7 * 6 + $n6 * 7 + $n5 * 8 + $n4 * 9 + $n3 * 2 + $n2 * 3 + $n1 * 4;
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) {
            $d2 = 0;
        }

        return sprintf('%d%d%d%d%d%d%d%d%d%d%d', $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $n10, $n11, $d1, $d2);
    }


    public function run(): void
    {
        Company::create([
            'id' => 1,
            'name' => 'Robson Gomes Pedreira Desenvolvimento de Software Ltda',
            'fantasy_name' => 'Tech Vanguard',
            'slug' => 'tech-vanguard',
            'cnpj' => '52648609000175',
            'cpf' => '05795097705',
            'email' => 'contato@techvanguard.com.br',
            'address' => 'Rua Ledo Ivo',
            'number' => '27',
            'neighborhood' => 'Jardim Nova Republica',
            'city' => 'São Gonçalo',
            'state' => 'RJ',
            'zip_code' => '24745290',
            'country' => 'Brasil',
            'phone' => '21981321890',
            'whatsapp' => '21981321890',
            'website' => 'https://techvanguard.com.br',
        ]);

        Company::create([
            'id' => 2,
            'name' => 'Emporio do Sabor',
            'fantasy_name' => 'Emporio do Sabor',
            'slug' => 'emporio-do-sabor',
            'cnpj' => $this->generateCnpj(),
            'cpf' => $this->generateCpf(),
            'email' => 'contato@emporiodosabor.com.br',
            'address' => 'Rua dos Bobos',
            'number' => '0',
            'neighborhood' => 'Zero',
            'city' => 'São Gonçalo',
            'state' => 'RJ',
            'zip_code' => '24745290',
            'country' => 'Brasil',
            'phone' => '21981321890',
            'whatsapp' => '21981321890',
            'website' => 'https://emporiodosabor.com.br',
        ]);

        Company::create([
            'id' => 3,
            'name' => 'Point do Oásis',
            'fantasy_name' => 'Point do Oásis',
            'slug' => 'point-do-oasis',
            'cnpj' => $this->generateCnpj(),
            'cpf' => $this->generateCpf(),
            'email' => 'contato@pointdooasis.com.br',
            'address' => 'Rua dos gatos',
            'number' => '40',
            'neighborhood' => 'Zero',
            'city' => 'São Gonçalo',
            'state' => 'RJ',
            'zip_code' => '24745790',
            'country' => 'Brasil',
            'phone' => '21981321890',
            'whatsapp' => '21981321890',
            'website' => 'https://pointdooasis.com.br',
        ]);
    }
}
