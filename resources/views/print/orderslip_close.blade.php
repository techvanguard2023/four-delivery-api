<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Fechamento de Comanda</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 58mm;
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

        .right {
            text-align: right;
        }

        .small {
            font-size: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
        }

        .item-row>div {
            flex: 1;
        }
    </style>
</head>

<body>

    <div class="center bold">FECHAMENTO DE COMANDA</div>
    <div class="center">Pedido #{{ $orderSlip->id }}</div>
    <div class="line"></div>

    <p><strong>Mesa:</strong> {{ $orderSlip->position ?? '—' }}</p>
    <p><strong>Cliente:</strong> {{ $orderSlip->customer_name ?? '—' }}</p>
    <p><strong>Atendente:</strong> {{ $orderSlip->user->name ?? '—' }}</p>

    <p><strong>Abertura:</strong> {{ $orderSlip->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Fechamento:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <div class="line"></div>
    <div class="bold">Itens:</div>

    @foreach ($orderSlip->orderSlipItems as $item)
        <div class="item-row">
            <div>{{ $item->quantity }}x {{ $item->item->name ?? 'Item' }}</div>
            <div class="right">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</div>
        </div>
        @if ($item->observation)
            <div class="small">Obs: {{ $item->observation }}</div>
        @endif
    @endforeach

    <div class="line"></div>

    @if ($orderSlip->adjustments->count())
        <div class="line"></div>
        <div class="bold">Ajustes:</div>

        @foreach ($orderSlip->adjustments as $adj)
            <div class="item-row">
                <div>
                    {{ ucfirst($adj->type) }}
                    @if ($adj->description)
                        - {{ $adj->description }}
                    @endif
                </div>
                <div class="right">
                    @if ($adj->value_type === 'percentage')
                        {{ $adj->value }}%
                    @else
                        R$ {{ number_format($adj->value, 2, ',', '.') }}
                    @endif
                </div>
            </div>
        @endforeach
    @endif


    <p class="right bold">Total: R$ {{ number_format($orderSlip->total_price, 2, ',', '.') }}</p>
    <p><strong>Status do Pagamento:</strong> {{ ucfirst($orderSlip->payment_status) }}</p>

    @if ($orderSlip->payment_method)
        <p><strong>Forma de Pagamento:</strong> {{ $orderSlip->payment_method }}</p>
    @endif

    @if ($orderSlip->last_status_id)
        <p><strong>Status final:</strong> {{ $orderSlip->status->name ?? '-' }}</p>
    @endif

    @if ($orderSlip->observation)
        <div class="line"></div>
        <p><strong>Observações da comanda:</strong><br>{{ $orderSlip->observation }}</p>
    @endif

    <div class="line"></div>
    <div class="center">
        <p>Volte sempre!</p>
    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>
