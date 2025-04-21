<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryStatuses = [
            ['id' => 1,  'name' => 'Aguardando Pagamento',      'description' => 'O pedido foi recebido, mas o pagamento ainda não foi confirmado.'],
            ['id' => 2,  'name' => 'Pagamento Confirmado',      'description' => 'O pagamento foi confirmado.'],
            ['id' => 3,  'name' => 'Pedido Recebido',           'description' => 'O pedido foi recebido pela cozinha ou pelo estabelecimento e está sendo preparado.'],
            ['id' => 4,  'name' => 'Em Preparação',             'description' => 'O pedido está sendo preparado ou cozinhado.'],
            ['id' => 5,  'name' => 'Pronto para Entrega',       'description' => 'O pedido está pronto e aguardando para ser retirado ou aguardando o entregador.'],
            ['id' => 6,  'name' => 'Aguardando Entregador',     'description' => 'O pedido está pronto, mas ainda não foi atribuído a um entregador.'],
            ['id' => 7,  'name' => 'Saiu para Entrega',         'description' => 'O entregador pegou o pedido e está a caminho do cliente.'],
            ['id' => 8,  'name' => 'Entregue',                  'description' => 'O pedido foi entregue ao cliente com sucesso.'],
            ['id' => 9,  'name' => 'Cancelado',                 'description' => 'O pedido foi cancelado, seja pelo cliente ou pelo estabelecimento.'],
            ['id' => 10, 'name' => 'Não Entregue',              'description' => 'O pedido não pôde ser entregue por algum motivo (ex.: endereço incorreto, cliente ausente).'],
            ['id' => 11, 'name' => 'Reembolsado',               'description' => 'O pagamento do pedido foi reembolsado ao cliente.'],
            ['id' => 12, 'name' => 'Em Espera',                 'description' => 'O pedido está aguardando alguma ação ou confirmação, como confirmação de endereço ou alteração no pedido.'],
            ['id' => 13, 'name' => 'Pedido Pendente',           'description' => 'O pedido foi recebido, mas está aguardando a confirmação manual por parte do estabelecimento.'],
            ['id' => 14, 'name' => 'Pedido Recusado',           'description' => 'O estabelecimento recusou o pedido (por exemplo, devido a falta de estoque ou outros problemas).'],
        ];

        $comandaStatuses = [
            ['id' => 15, 'name' => 'Comanda Aberta',                      'description' => 'Status para atribuir quando a comanda estiver aberta e o cliente consumindo no estabelecimento.'],
            ['id' => 16, 'name' => 'Comanda Fechada',                     'description' => 'Status para atribuir quando a comanda já estiver sido pagada e o cliente já tiver saído do estabelecimento.'],
            ['id' => 17, 'name' => 'Comanda Pendente Fechamento',         'description' => 'Status para atribuir quando o Garçom solicitar o fechamento da comanda.'],
            ['id' => 18, 'name' => 'Comanda Pendente Pagamento',          'description' => 'Status para atribuir quando o Garçom solicitar o pagamento da comanda.'],
            ['id' => 19, 'name' => 'Comanda Pendente Cancelamento',       'description' => 'Status para atribuir quando o Cliente solicitar o cancelamento da comanda.'],
            ['id' => 20, 'name' => 'Comanda Cancelada',                   'description' => 'Status para atribuir a comanda for cancelada.'],
            
        ];

        foreach (array_merge($deliveryStatuses, $comandaStatuses) as $status) {
            Status::updateOrCreate(['id' => $status['id']], $status);
        }
    }
}
