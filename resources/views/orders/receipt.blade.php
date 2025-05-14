<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo Pedido #{{ $order->id }}</title>
    <style>
        * {
            font-size: 12px;
            font-family: monospace;
        }
        body {
            margin: 0;
            padding: 10px;
            width: 260px; /* Aproximadamente 58mm */
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .bold {
            font-weight: bold;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <div class="center bold">Pedido #{{ $order->id }}</div>
    <div class="center">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</div>

    <div class="line"></div>

    <div class="mb-2">
        <div class="bold">Cliente:</div>
        <div>{{ $order->customer->name }}</div>
        <div>{{ $order->customer->phone }}</div>
    </div>

    {{-- @php $address = $order->delivery_addresses[0]; @endphp
    <div class="mb-2">
        <div class="bold">Endereço:</div>
        <div>{{ $address->address }}, {{ $address->number }}</div>
        @if ($address->complement)
            <div>{{ $address->complement }}</div>
        @endif
        @if ($address->reference_point)
            <div>Ponto Ref: {{ $address->reference_point }}</div>
        @endif
        <div>{{ $address->neighborhood }} - {{ $address->city }}/{{ $address->state }}</div>
        <div>CEP: {{ $address->zip_code }}</div>
    </div> --}}

    <div class="line"></div>

    {{-- <div class="bold mb-2">Itens:</div>
    @foreach ($order->order_items as $item)
        <div>
            {{ $item->quantity }}x {{ $item->item_name }}
        </div>
        @if ($item->item_description)
            <div>{{ $item->item_description }}</div>
        @endif
        @if ($item->observation)
            <div>Obs: {{ $item->observation }}</div>
        @endif
        <div class="mb-2">R$ {{ number_format($item->price, 2, ',', '.') }}</div>
    @endforeach --}}

    <div class="line"></div>

    <div>
        <div class="bold">Status do Pedido:</div>
        <div>{{ ucfirst($order->status) }}</div>
    </div>

    <div>
        <div class="bold">Pagamento:</div>
        <div>{{ $order->payment_status === 'pending' ? 'Pendente' : 'Pago' }}</div>
    </div>

    <div class="line"></div>

    <div class="bold">
        Total: R$ {{ number_format($order->total_price, 2, ',', '.') }}
    </div>

    <div class="line"></div>

    <div class="center">Obrigado pela preferência!</div>

</body>
</html>
