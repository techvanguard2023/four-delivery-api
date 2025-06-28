<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comanda #{{ $orderSlip->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;1
            font-size: 16px;
            width: 58mm;
            margin-bottom: 20px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 6px 0;
        }

        .bold {
            font-weight: bold;
        }

        .item {
            margin-bottom: 4px;
        }
        .item_list {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 2rem;
        }
        .session_values {
            display: flex;
            flex-direction: column;
            gap: .5rem;
            margin-bottom: 2rem;
            margin-top: 2rem;
        }
        .container_items_values {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="center">
        <div class="bold">COMANDA</div>
        <div>Pedido #{{ $orderSlip->id }}</div>
        <div class="line"></div>
    </div>

    <div class="session_values">
        <div class="container_items_values">
            <span><strong>Mesa:</strong></span>
            <span>{{ $orderSlip->position ?? '—' }}</span>
        </div>
        <div class="container_items_values">
            <span><strong>Cliente:</strong></span>
            <span>{{ $orderSlip->customer_name ?? '—' }}</span>
        </div>
        <div class="container_items_values">
            <span><strong>Atend.:</strong></span>
            <span>{{ isset($orderSlip->user->name) ? Str::before($orderSlip->user->name, ' ') : '—' }}</span>
        </div>
        <div class="container_items_values">
            <span><strong>Data:</strong></span>
            <span>{{ $orderSlip->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>
   
    <div class="line"></div>

    <div class="session_values">
        @foreach ($orderSlip->orderSlipItems as $item)
            <div class="item">
                <div class='item_list'>
                    <div>
                        <span class="bold">{{ $item->quantity }}x</span>
                    </div>
                     
                    <span>{{ $item->item->name ?? 'Produto' }}</span>
                </div>
                
                @if ($item->observation)
                    <small><em>Obs: {{ $item->observation }}</em></small>
                @endif
            </div>
        @endforeach
    </div>

    <div class="line"></div>

    @php
        $subtotal = $orderSlip->total_price;
        $desconto = $orderSlip->discount ?? 0;
        $subtotalComDesconto = $subtotal - $desconto;
        $couvert = $orderSlip->couvert ?? 0;
        $taxaPercentual = $orderSlip->percentage_tax ?? 0;
        $taxaServico = ($subtotalComDesconto * $taxaPercentual) / 100;
        $total = $subtotalComDesconto + $taxaServico + $couvert;
    @endphp



    <div class="session_values">
        <div class="container_items_values">
            <span class="bold">Subtotal:</span>
            <span>R${{ number_format($subtotal, 2, ',', '.') }}</span>
        </div>

        @if ($desconto) 
            <div class="container_items_values">
                <span class="bold">Desconto:</span>
                <span>R${{ number_format($desconto, 2, ',', '.') }}</span>
            </div>
            <div class="container_items_values">
                <span class="bold">Sub. + Desc.:</span>
                <span>R${{ number_format($subtotalComDesconto, 2, ',', '.') }}</span>
            </div>

            <div class="line"></div>
        @endif

        @if ($taxaPercentual) 
            <div class="container_items_values">
                <span class="bold">Tx. Serv. ({{ $taxaPercentual }}%):</span>
                <span>R${{ number_format($taxaServico, 2, ',', '.') }}</span>
            </div>
        @endif

        @if ($couvert) 
            <div class="container_items_values">
                <span class="bold">Couvert:</span>
                <span>R${{ number_format($couvert, 2, ',', '.') }}</span>
            </div>
        @endif
        
        @if ($taxaPercentual || $couvert)
            <div class="line"></div>
        @endif
        
        <div class="container_items_values">
            <span class="bold">Total:</span>
            <span>R${{ number_format($total, 2, ',', '.') }}</span>
        </div>
    </div>
    

    <div class="center">
        <p>Muito Obrigado</p>
        <p>Volte Sempre!!!</p>
    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>
