<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Print KOT</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui;
        }

        .center {
            text-align: center
        }

        .muted {
            color: #555;
            font-size: 12px
        }

        .big {
            font-size: 28px;
            font-weight: 800
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px
        }

        td,
        th {
            padding: 6px 0;
            border-bottom: 1px dashed #aaa;
            vertical-align: top
        }
    </style>
</head>

<body onload="window.print(); setTimeout(()=>{ window.location.href='{{ route('kasir.kitchen_today') }}'; }, 350);">
    <div class="center">
        <div style="font-weight:800">ASTABRATA RESTAURANT</div>
        <div class="muted">CAPTAIN ORDER (KOT)</div>
    </div>

    <div class="center" style="margin-top:10px">
        <div class="muted">QUEUE NO</div>
        <div class="big">#{{ str_pad((string) $ticket->queue_no, 3, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div style="margin-top:10px">
        <div><b>Bill:</b> {{ $ticket->ticket_no }}</div>
        <div><b>Meja:</b> {{ $ticket->transaction->table?->code ?? '-' }} </div>
        {{-- <div><b>Kasir:</b> {{ $ticket->transaction->cashier?->name }}</div> --}}
        <div><b>Waktu:</b> {{ $ticket->transaction->sent_to_kitchen_at ?? $ticket->created_at }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th align="left">Item</th>
                <th align="right">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket->transaction->items as $it)
                <tr>
                    <td>
                        <div><b>{{ $it->product_name }}</b></div>
                        @if ($it->note)
                            <div class="muted">Note: {{ $it->note }}</div>
                        @endif
                    </td>
                    <td align="right"><b>{{ $it->qty }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
