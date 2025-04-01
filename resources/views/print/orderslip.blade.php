<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comanda #{{ $orderSlip->id }}</title>
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

        .item {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    <div class="center">
        <div class="bold">COMANDA</div>
        <div>Pedido #{{ $orderSlip->id }}</div>
        <div class="line"></div>
    </div>

    <p><strong>Mesa:</strong> {{ $orderSlip->position ?? '—' }}</p>
    <p><strong>Cliente:</strong> {{ $orderSlip->customer_name ?? '—' }}</p>
    <p><strong>Atendente:</strong> {{ $orderSlip->user->name ?? '—' }}</p>
    <p><strong>Data:</strong> {{ $orderSlip->created_at->format('d/m/Y H:i') }}</p>

    <div class="line"></div>

    <div>
        @foreach ($orderSlip->orderSlipItems as $item)
            <div class="item">
                {{ $item->quantity }}x {{ $item->item->name ?? 'Produto' }}
                @if ($item->observation)
                    <br><small><em>Obs: {{ $item->observation }}</em></small>
                @endif
            </div>
        @endforeach
    </div>

    <div class="line"></div>
    <div class="bold">
        Total: R$ {{ number_format($orderSlip->total_price, 2, ',', '.') }}
    </div>

    <div class="center">
        <p>Obrigado!</p>
    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>
