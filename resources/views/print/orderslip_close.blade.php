<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Fechamento de Comanda #{{ $orderSlip->id }}</title>
    <style>
        @page {
            size: {{ ($printLayout ?? '80mm') === '80mm' ? '80mm' : '58mm' }} auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            width: {{ ($printLayout ?? '80mm') === '80mm' ? '72mm' : '48mm' }};
            margin: 0;
            padding: 5px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .line {
            border-bottom: 1px solid #000;
            margin: 4px 0;
        }

        .double-line {
            border-bottom: 2px solid #000;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            text-align: left;
            border-bottom: 1px solid #000;
        }

        .right {
            text-align: right;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .header {
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .info-row {
            margin-bottom: 2px;
        }

        .items-table {
            margin: 10px 0;
        }

        .totals {
            font-size: 16px;
            margin: 10px 0;
        }

        .footer {
            margin-top: 15px;
            font-size: 11px;
        }

        .signature {
            margin-top: 30px;
            border-top: 1px dashed #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 2px;
        }
    </style>
</head>

<body>

    <div class="header center">
        <h1 class="bold uppercase">{{ $orderSlip->company->fantasy_name ?? $orderSlip->company->name }}</h1>
        <div class="uppercase">{{ $orderSlip->company->name }}</div>
        <div>{{ $orderSlip->company->address }}, {{ $orderSlip->company->number }} - {{ $orderSlip->company->neighborhood }}</div>
        <div>{{ $orderSlip->company->city }} - {{ $orderSlip->company->zip_code }} - {{ $orderSlip->company->state }}/{{ $orderSlip->company->country ?? 'BR' }}</div>
        <div>({{ substr($orderSlip->company->phone, 0, 2) }}) {{ substr($orderSlip->company->phone, 2, 4) }}-{{ substr($orderSlip->company->phone, 6) }}</div>
        <div class="flex-between">
            <span>CNPJ : {{ $orderSlip->company->cnpj }}</span>
            <span>IE : {{ $orderSlip->company->ie ?? 'ISENTO' }}</span>
        </div>
    </div>

    <div class="center bold uppercase" style="margin-bottom: 5px;">FECHAMENTO DE COMANDA</div>

    <div class="info-row bold uppercase">
        CLIENTE : {{ $orderSlip->customer_name ?? 'CONSUMIDOR FINAL' }}
    </div>

    <div class="flex-between" style="align-items: flex-end;">
        <div>
            <div>ABERTURA: {{ $orderSlip->created_at->format('d/m/Y H:i') }}</div>
            <div>FECHAMENTO: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div class="center">
            <div style="font-size: 8px;">PEDIDO</div>
            <div class="bold" style="font-size: 18px;">Nº {{ str_pad($orderSlip->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <div class="double-line"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="20%">CODIGO</th>
                <th width="55%">DESCRIÇÃO</th>
                <th width="25%" class="right">VALOR</th>
            </tr>
            <tr>
                <th></th>
                <th>QTD x UNIT</th>
                <th class="right">R$ VALOR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderSlip->orderSlipItems as $item)
                @php
                    $itemCode = str_pad($item->item_id, 5, '0', STR_PAD_LEFT);
                @endphp
                <tr>
                    <td class="bold">{{ $itemCode }}</td>
                    <td class="bold uppercase">{{ $item->item->name }}</td>
                    <td class="right bold">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="right">{{ $item->quantity }} x {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
                @if ($item->observation)
                    <tr>
                        <td></td>
                        <td colspan="2" style="font-size: 10px;"><em>Obs: {{ $item->observation }}</em></td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="double-line"></div>

    @php
        $subtotal = $orderSlip->total_price;
        $desconto = $orderSlip->discount ?? 0;
        $couvert = $orderSlip->couvert ?? 0;
        $taxaPercentual = $orderSlip->percentage_tax ?? 0;
        $taxaServico = (($subtotal - $desconto) * $taxaPercentual) / 100;
        $total = $subtotal - $desconto + $taxaServico + $couvert;
    @endphp

    <div class="totals">
        <div class="flex-between bold">
            <span>Total da Nota R$</span>
            <span>{{ number_format($total, 2, ',', '.') }}</span>
        </div>
    </div>

    <div class="line"></div>

    <div class="info-row bold uppercase">
        VENDEDOR(A) : {{ $orderSlip->user->name ?? 'SISTEMA' }}
    </div>
    
    <div class="info-row bold uppercase">
        MESA/POSIÇÃO : {{ $orderSlip->position ?? '—' }}
    </div>

    <div class="line"></div>

    <div class="footer">
        @if($orderSlip->observations)
            <div style="margin-bottom: 15px;">
                Notas: {{ $orderSlip->observations }}
            </div>
        @endif

        <div style="margin-bottom: 20px;">
            Confirmo que recebi os itens acima descritos.
        </div>

        <div class="signature center">
            ASSINATURA DO CLIENTE
        </div>

        <div class="line" style="margin-top: 20px;"></div>
        <div class="center bold">
            * OBRIGADO E VOLTE SEMPRE *
        </div>
    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>

