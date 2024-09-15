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
            'name' => 'Aguardando Pagamento',
            'description' => 'O pedido foi recebido, mas o pagamento ainda não foi confirmado.'
        ]);

        Status::create([
            'name' => 'Pagamento Confirmado',
            'description' => 'O pagamento foi confirmado.'
        ]);

        Status::create([
            'name' => 'Pedido Recebido',
            'description' => 'O pedido foi recebido pela cozinha ou pelo estabelecimento e está sendo preparado.'
        ]);

        Status::create([
            'name' => 'Em Preparação',
            'description' => 'O pedido está sendo preparado ou cozinhado.'
        ]);

        Status::create([
            'name' => 'Pronto para Retirada',
            'description' => 'O pedido está pronto e aguardando para ser retirado pelo entregador.'
        ]);

        Status::create([
            'name' => 'Pronto para Retirada pelo Cliente',
            'description' => 'O pedido está pronto e aguardando o cliente para retirar.'
        ]);

        Status::create([
            'name' => 'Aguardando Entregador',
            'description' => 'O pedido está pronto, mas ainda não foi atribuído a um entregador.'
        ]);

        Status::create([
            'name' => 'Saiu para Entrega',
            'description' => 'O entregador pegou o pedido e está a caminho do cliente.'
        ]);

        Status::create([
            'name' => 'Entregue',
            'description' => 'O pedido foi entregue ao cliente com sucesso.'
        ]);

        Status::create([
            'name' => 'Cancelado',
            'description' => 'O pedido foi cancelado, seja pelo cliente ou pelo estabelecimento.'
        ]);

        Status::create([
            'name' => 'Não Entregue',
            'description' => 'O pedido não pôde ser entregue por algum motivo (ex.: endereço incorreto, cliente ausente).'
        ]);

        Status::create([
            'name' => 'Reembolsado',
            'description' => 'O pagamento do pedido foi reembolsado ao cliente.'
        ]);

        Status::create([
            'name' => 'Em Espera',
            'description' => 'O pedido está aguardando alguma ação ou confirmação, como confirmação de endereço ou alteração no pedido.'
        ]);

        Status::create([
            'name' => 'Pedido Pendente',
            'description' => 'O pedido foi recebido, mas está aguardando a confirmação manual por parte do estabelecimento.'
        ]);

        Status::create([
            'name' => 'Pedido Recusado',
            'description' => 'O estabelecimento recusou o pedido (por exemplo, devido a falta de estoque ou outros problemas).'
        ]);

        Status::create([
            'name' => 'Pedido Aberto',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente estiver consumindo no estabelecimento e for fazer o pagamento somente no final.'
        ]);

        Status::create([
            'name' => 'Pedido Fechado',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente consumiu no estabelecimento e acabou de fazer o pagamento.'
        ]);

        Status::create([
            'name' => 'Pedido Finalizado',
            'description' => 'Status para atribuir quando a venda for presencial e o cliente consumiu no estabelecimento e já foi embora.'
        ]);
    }
}
