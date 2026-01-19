<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Receipt - {{ $tx->bill_no ?? '#' . $tx->id }}</title>
    <style>
        /* ===== Thermal-friendly, clean receipt ===== */
        :root {
            --text: #111;
            --muted: #666;
            --line: #D0D0D0;
            --dash: #9A9A9A;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
            color: var(--text);
            background: #fff;
            font-size: 12px;
            line-height: 1.35;
        }

        /* width: cocok thermal 80mm; masih aman untuk A4 */
        .receipt {
            width: 320px;
            max-width: 100%;
            margin: 0 auto;
            padding: 12px 12px 18px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: var(--muted);
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .brand {
            font-weight: 900;
            letter-spacing: .6px;
            font-size: 16px;
            text-transform: uppercase;
        }

        .subtitle {
            margin-top: 2px;
            font-weight: 700;
            letter-spacing: .8px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .hr {
            border: 0;
            border-top: 1px solid var(--line);
            margin: 10px 0;
        }

        .dash {
            border: 0;
            border-top: 1px dashed var(--dash);
            margin: 10px 0;
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4px;
            margin-top: 8px;
        }

        .meta .row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .meta b {
            font-weight: 700;
        }

        .meta .label {
            color: var(--muted);
        }

        .meta .value {
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            padding: 6px 0;
            vertical-align: top;
        }

        thead th {
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
            border-bottom: 1px dashed var(--dash);
            padding-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        tbody tr:last-child td {
            border-bottom: 1px dashed var(--dash);
            padding-bottom: 10px;
        }

        .item-name {
            font-weight: 600;
        }

        .item-note {
            margin-top: 2px;
            font-size: 11px;
            color: var(--muted);
        }

        .totals {
            margin-top: 10px;
            display: grid;
            gap: 6px;
        }

        .totals .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .totals .label {
            color: var(--muted);
        }

        .totals .value {
            font-weight: 700;
        }

        .totals .grand {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .3px;
        }

        .payment {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid var(--line);
            display: grid;
            gap: 6px;
        }

        .payment .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 14px;
            text-align: center;
            color: var(--muted);
            font-size: 11px;
        }

        .footer .thanks {
            margin-top: 6px;
            font-weight: 800;
            color: #222;
            font-size: 12px;
        }

        /* Print tweaks */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .receipt {
                width: auto;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print(); setTimeout(()=>{ window.location.href='{{ route('kasir.kitchen_today') }}'; }, 350);">
    @php
        // Format tanggal Indonesia yang rapi (tanpa Carbon locale ribet)
        $dt = $tx->paid_at ?? $tx->created_at;
        $dtStr = is_string($dt) ? $dt : $dt->format('d/m/Y H:i');
    @endphp

    <div class="receipt">

        <div class="center">
            <div class="brand">ASTABRATA</div>
            <div class="subtitle">Restaurant • Payment Receipt</div>
            <div class="muted" style="margin-top:4px">Jl. — Empunala No.87 • Telp — 085748232406</div>
        </div>

        <hr class="hr">

        <div class="meta">
            <div class="row">
                <span class="label">Bill</span>
                <span class="value mono">{{ $tx->bill_no ?? '#' . $tx->id }}</span>
            </div>
            <div class="row">
                <span class="label">Meja</span>
                <span class="value">{{ $tx->table?->code ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Tanggal</span>
                <span class="value">{{ $dtStr }}</span>
            </div>
            <div class="row">
                <span class="label">Kasir</span>
                <span class="value">{{ $tx->cashier?->name ?? '-' }}</span>
            </div>
        </div>

        <hr class="dash">

        <table>
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="right" style="width:42px;">Qty</th>
                    <th align="right" style="width:98px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tx->items as $it)
                    <tr>
                        <td>
                            <div class="item-name">{{ $it->product_name }}</div>
                            @if (!empty($it->note))
                                <div class="item-note">Note: {{ $it->note }}</div>
                            @endif
                            <div class="item-note">
                                @php
                                    // unit_price bisa kosong kalau kamu belum simpan, fallback subtotal/qty
                                    $unit = $it->unit_price ?? ($it->qty ? $it->subtotal / $it->qty : 0);
                                @endphp
                                Rp {{ number_format($unit, 0, ',', '.') }} / item
                            </div>
                        </td>
                        <td align="right" class="mono">{{ $it->qty }}</td>
                        <td align="right" class="mono">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="row">
                <span class="label">Subtotal</span>
                <span class="value mono">Rp {{ number_format($tx->subtotal, 0, ',', '.') }}</span>
            </div>

            @if ((float) ($tx->discount ?? 0) > 0)
                <div class="row">
                    <span class="label">Diskon</span>
                    <span class="value mono">- Rp {{ number_format($tx->discount, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ((float) ($tx->tax ?? 0) > 0)
                <div class="row">
                    <span class="label">Pajak</span>
                    <span class="value mono">Rp {{ number_format($tx->tax, 0, ',', '.') }}</span>
                </div>
            @endif

            @if ((float) ($tx->service ?? 0) > 0)
                <div class="row">
                    <span class="label">Service</span>
                    <span class="value mono">Rp {{ number_format($tx->service, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="row">
                <span class="label grand">Total</span>
                <span class="value grand mono">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="payment">
            @if ($tx->payment)
                <div class="row">
                    <span class="label">Pembayaran</span>
                    <span class="badge">{{ strtoupper($tx->payment->method ?? 'CASH') }}</span>
                </div>

                @if (($tx->payment->method ?? '') === 'cash')
                    <div class="row">
                        <span class="label">Diterima</span>
                        <span class="value mono">Rp
                            {{ number_format($tx->payment->cash_received ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Kembalian</span>
                        <span class="value mono">Rp
                            {{ number_format($tx->payment->change_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="row">
                        <span class="label">Ref</span>
                        <span class="value mono">{{ $tx->payment->reference_no ?? '-' }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Status</span>
                        <span
                            class="value">{{ ($tx->payment->status ?? '') === 'pending_verification' ? 'Menunggu verifikasi' : 'Terverifikasi' }}</span>
                    </div>
                @endif
            @else
                <div class="row">
                    <span class="label">Pembayaran</span>
                    <span class="value">-</span>
                </div>
            @endif
        </div>

        <div class="footer">
            <div class="muted">Simpan struk ini sebagai bukti pembayaran.</div>
            <div class="thanks">Terima kasih 🙏</div>
        </div>

    </div>
</body>

</html>
