<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'id' => 1,
            'name' => 'Aguardando Pagamento',
            'description' => 'O pedido foi recebido, mas o pagamento ainda não foi confirmado.'
        ]);

        Status::create([
            'id' => 2,
            'name' => 'Pagamento Confirmado',
            'description' => 'O pagamento foi confirmado.'
        ]);

        Status::create([
            'id' => 3,
            'name' => 'Pedido Recebido',
            'description' => 'O pedido foi recebido pela cozinha ou pelo estabelecimento e está sendo preparado.'
        ]);

        Status::create([
            'id' => 4,
            'name' => 'Em Preparação',
            'description' => 'O pedido está sendo preparado ou cozinhado.'
        ]);

        Status::create([
            'id' => 5,
            'name' => 'Pronto para Entrega',
            'description' => 'O pedido está pronto e aguardando para ser retirado ou aguardando o entregador.'
        ]);

        Status::create([
            'id' => 6,
            'name' => 'Aguardando Entregador',
            'description' => 'O pedido está pronto, mas ainda não foi atribuído a um entregador.'
        ]);

        Status::create([
            'id' => 7,
            'name' => 'Saiu para Entrega',
            'description' => 'O entregador pegou o pedido e está a caminho do cliente.'
        ]);

        Status::create([
            'id' => 8,
            'name' => 'Entregue',
            'description' => 'O pedido foi entregue ao cliente com sucesso.'
        ]);

        Status::create([
            'id' => 9,
            'name' => 'Cancelado',
            'description' => 'O pedido foi cancelado, seja pelo cliente ou pelo estabelecimento.'
        ]);

        Status::create([
            'id' => 10,
            'name' => 'Não Entregue',
            'description' => 'O pedido não pôde ser entregue por algum motivo (ex.: endereço incorreto, cliente ausente).'
        ]);

        Status::create([
            'id' => 11,
            'name' => 'Reembolsado',
            'description' => 'O pagamento do pedido foi reembolsado ao cliente.'
        ]);

        Status::create([
            'id' => 12,
            'name' => 'Em Espera',
            'description' => 'O pedido está aguardando alguma ação ou confirmação, como confirmação de endereço ou alteração no pedido.'
        ]);

        Status::create([
            'id' => 13,
            'name' => 'Pedido Pendente',
            'description' => 'O pedido foi recebido, mas está aguardando a confirmação manual por parte do estabelecimento.'
        ]);

        Status::create([
            'id' => 14,
            'name' => 'Pedido Recusado',
            'description' => 'O estabelecimento recusou o pedido (por exemplo, devido a falta de estoque ou outros problemas).'
        ]);

        Status::create([
            'id' => 15,
            'name' => 'Pedido Aberto',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente estiver consumindo no estabelecimento e for fazer o pagamento somente no final.'
        ]);

        Status::create([
            'id' => 16,
            'name' => 'Pedido ou Comanda Fechada',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente consumiu no estabelecimento e acabou de fazer o pagamento.'
        ]);

        Status::create([
            'id' => 17,
            'name' => 'Pedido Finalizado',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente consumiu no estabelecimento e já foi embora.'
        ]);
    }
}