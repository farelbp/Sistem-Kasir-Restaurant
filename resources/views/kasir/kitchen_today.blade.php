@extends('layouts.app')
@section('content')
    @php
        $selectedId = (int) request('ticket_id', $tickets->first()->id ?? 0);
        $selectedTicket = $tickets->firstWhere('id', $selectedId) ?? $tickets->first();
        $selectedTx = $selectedTicket?->transaction;
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Captain Order Today</div>
            <div class="text-sm text-slate-500">Tanggal: {{ $today }}</div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('kasir.pos') }}" class="rounded-xl border px-3 py-2 hover:bg-slate-50">Kembali ke POS</a>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-4">

        {{-- LEFT --}}
        <div class="lg:col-span-4">
            <div class="rounded-3xl border bg-white overflow-hidden">
                <div class="p-4 border-b bg-white/70">
                    <div class="font-semibold">Daftar Order</div>
                    <div class="text-xs text-slate-500">Klik untuk melihat detail</div>
                </div>

                <div class="p-3">
                    <input id="qSearch" placeholder="Cari: queue / bill / meja / kasir..."
                        class="w-full rounded-2xl border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-200" />
                </div>

                <div class="max-h-[72vh] overflow-y-auto p-3 space-y-2" id="ticketList">
                    @forelse($tickets as $t)
                        @php
                            $tx = $t->transaction;

                            $queueStr = str_pad((string) $t->queue_no, 3, '0', STR_PAD_LEFT);
                            $isSelected = $selectedTicket && (int) $selectedTicket->id === (int) $t->id;

                            $status = $tx->status ?? '-';
                            $total = (int) ($tx->grand_total ?? 0);
                            $tableCode = $tx->table?->code ?? '-';
                            $cashierName = $tx->cashier?->name ?? '-';

                            $itemsPayload =
                                $tx?->items
                                    ?->map(function ($it) {
                                        return [
                                            'qty' => (int) $it->qty,
                                            'name' => (string) $it->product_name,
                                            'note' => (string) ($it->note ?? ''),
                                            'subtotal' => (int) $it->subtotal,
                                        ];
                                    })
                                    ->values()
                                    ->all() ?? [];

                            // SAFE: base64 JSON (utf-8), tidak akan pecah di attribute
                            $itemsB64 = base64_encode(json_encode($itemsPayload, JSON_UNESCAPED_UNICODE));

                            $payMethod = $tx->payment?->method ?? '';
                            $payRef = $tx->payment?->reference_no ?? '';
                            $paidAt = $tx->paid_at ?? '';
                        @endphp

                        <button type="button"
                            class="w-full text-left rounded-2xl border p-3 hover:bg-slate-50 transition
                        {{ $isSelected ? 'bg-slate-50 border-slate-300' : 'bg-white' }}"
                            data-ticket-id="{{ $t->id }}" data-ticket-no="{{ $t->ticket_no }}"
                            data-queue-no="{{ $t->queue_no }}" data-table="{{ $tableCode }}"
                            data-cashier="{{ $cashierName }}" data-status="{{ $status }}"
                            data-total="{{ $total }}" data-tx-id="{{ $tx->id }}"
                            data-items-b64="{{ $itemsB64 }}" data-pay-method="{{ $payMethod }}"
                            data-pay-ref="{{ $payRef }}" data-paid-at="{{ $paidAt }}"
                            onclick="selectTicket(this)">

                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-xs text-slate-500">Queue</div>
                                    <div class="text-2xl font-extrabold" style="color:var(--gold)">#{{ $queueStr }}
                                    </div>
                                </div>

                                <div class="text-right text-xs text-slate-600">
                                    <div>
                                        <span class="text-slate-500">Bill:</span>
                                        <span class="font-semibold">{{ $t->ticket_no }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Meja:</span>
                                        <span class="font-semibold">{{ $tableCode }}</span>
                                    </div>

                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-[11px]
                    {{ $status === 'paid'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                        : ($status === 'pending_verification'
                            ? 'bg-amber-50 border-amber-200 text-amber-800'
                            : 'bg-slate-50 border-slate-200 text-slate-700') }}">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                <div>{{ $cashierName }}</div>
                                <div class="font-semibold text-slate-700">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                        </button>
                    @empty
                        <div class="rounded-2xl border bg-white p-5 text-slate-500">Belum ada Captain Order hari ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="lg:col-span-8">
            <div class="rounded-3xl border bg-white overflow-hidden">
                <div class="p-4 border-b bg-white/70 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <div class="font-semibold">Detail Order</div>
                        <div class="text-xs text-slate-500">Item, print, dan pembayaran</div>
                    </div>

                    <div class="flex gap-2">
                        <a id="btnPrintKot" href="{{ $selectedTicket ? route('print.kot', $selectedTicket->id) : '#' }}"
                            class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                            Print KOT
                        </a>

                        {{-- Print struk hanya kalau PAID --}}
                        <a id="btnPrintReceipt"
                            href="{{ $selectedTx && $selectedTx->status === 'paid' ? route('print.receipt', $selectedTx->id) : '#' }}"
                            class="rounded-xl border px-3 py-2 hover:bg-slate-50 {{ $selectedTx && $selectedTx->status === 'paid' ? '' : 'hidden' }}">
                            Print Struk
                        </a>
                    </div>
                </div>

                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="rounded-2xl border p-3">
                            <div class="text-xs text-slate-500">Queue No</div>
                            <div id="dQueue" class="text-3xl font-extrabold" style="color:var(--gold)">
                                @if ($selectedTicket)
                                    #{{ str_pad((string) $selectedTicket->queue_no, 3, '0', STR_PAD_LEFT) }}@else-
                                @endif
                            </div>
                        </div>

                        <div class="rounded-2xl border p-3">
                            <div class="text-xs text-slate-500">Bill</div>
                            <div id="dBill" class="font-semibold">{{ $selectedTicket?->ticket_no ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Status: <span id="dStatus"
                                    class="font-semibold">{{ strtoupper($selectedTx->status ?? '-') }}</span>
                            </div>
                        </div>

                        <div class="rounded-2xl border p-3">
                            <div class="text-xs text-slate-500">Meja</div>
                            <div id="dTable" class="font-semibold">{{ $selectedTx?->table?->code ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Kasir: <span id="dCashier"
                                    class="font-semibold">{{ $selectedTx?->cashier?->name ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="rounded-2xl border p-3">
                            <div class="text-xs text-slate-500">Total</div>
                            <div id="dTotal" class="text-xl font-extrabold">
                                Rp {{ number_format((int) ($selectedTx->grand_total ?? 0), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border p-4">
                            <div class="font-semibold mb-2">Item</div>

                            <ul id="dItems" class="space-y-2 text-sm">
                                @if ($selectedTx)
                                    @foreach ($selectedTx->items as $it)
                                        <li class="flex justify-between gap-3">
                                            <div>
                                                <div class="font-medium">{{ $it->qty }}x {{ $it->product_name }}
                                                </div>
                                                @if ($it->note)
                                                    <div class="text-xs text-slate-500">Note: {{ $it->note }}</div>
                                                @endif
                                            </div>
                                            <div class="text-slate-600 whitespace-nowrap">Rp
                                                {{ number_format($it->subtotal, 0, ',', '.') }}</div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-slate-500">Pilih order di sebelah kiri.</li>
                                @endif
                            </ul>

                            <div class="mt-3 flex justify-between border-t pt-2 font-bold">
                                <span>Total</span>
                                <span id="dTotalBottom" style="color:var(--gold)">
                                    Rp {{ number_format((int) ($selectedTx->grand_total ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="rounded-2xl border p-4">
                            <div class="font-semibold mb-2">Pembayaran</div>

                            <div id="paidBox"
                                class="{{ $selectedTx && $selectedTx->status === 'paid' ? '' : 'hidden' }}">
                                <div class="rounded-xl bg-green-50 border border-green-200 p-3 text-sm">
                                    <div class="font-semibold text-green-700">Sudah dibayar</div>
                                    <div class="text-green-700" id="paidMeta">
                                        {{ $selectedTx?->payment?->method }} • {{ $selectedTx?->paid_at }}
                                    </div>
                                </div>
                            </div>

                            <div id="unpaidBox"
                                class="{{ $selectedTx && $selectedTx->status !== 'paid' ? '' : 'hidden' }}">
                                <form method="POST" action="{{ route('kasir.pay.cash') }}" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="transaction_id" id="payTxId"
                                        value="{{ $selectedTx->id ?? '' }}" />
                                    <label class="text-sm font-medium">Cash Received</label>
                                    <input type="number" name="cash_received" min="0" step="1000"
                                        class="w-full rounded-xl border px-3 py-2" placeholder="Uang diterima" />
                                    <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">
                                        Bayar Cash & Print
                                    </button>
                                </form>

                                <div class="my-3 border-t"></div>

                                <div id="transferPendingBox"
                                    class="{{ $selectedTx && $selectedTx->status === 'pending_verification' ? '' : 'hidden' }}">
                                    <form method="POST" action="{{ route('kasir.pay.verify_transfer') }}">
                                        @csrf
                                        <input type="hidden" name="transaction_id" id="verifyTxId"
                                            value="{{ $selectedTx->id ?? '' }}" />
                                        <button class="w-full rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">
                                            Verifikasi Transfer (Manual)
                                        </button>
                                    </form>
                                    <div class="mt-2 text-xs text-slate-500">
                                        Ref: <span
                                            id="transferRef">{{ $selectedTx?->payment?->reference_no ?? '-' }}</span>
                                    </div>
                                </div>

                                <div id="transferMarkBox"
                                    class="{{ $selectedTx && $selectedTx->status !== 'pending_verification' ? '' : 'hidden' }}">
                                    <form method="POST" action="{{ route('kasir.pay.transfer_pending') }}"
                                        class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="transaction_id" id="markTxId"
                                            value="{{ $selectedTx->id ?? '' }}" />
                                        <label class="text-sm font-medium">Transfer Manual (opsional)</label>
                                        <input name="reference_no" class="w-full rounded-xl border px-3 py-2"
                                            placeholder="No referensi (opsional)" />
                                        <button class="w-full rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">
                                            Tandai Pending Verifikasi
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div id="emptyPayBox" class="{{ $selectedTx ? 'hidden' : '' }}">
                                <div class="text-slate-500 text-sm">Pilih order di sebelah kiri untuk memproses pembayaran.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        const q = document.getElementById('qSearch');
        const list = document.getElementById('ticketList');

        function normalize(s) {
            return (s || '').toString().toLowerCase();
        }

        q && q.addEventListener('input', () => {
            const keyword = normalize(q.value);
            const items = list.querySelectorAll('button[data-ticket-id]');
            items.forEach(btn => {
                const hay = [
                    btn.dataset.queueNo,
                    btn.dataset.ticketNo,
                    btn.dataset.table,
                    btn.dataset.cashier,
                    btn.dataset.status
                ].map(normalize).join(' ');
                btn.style.display = hay.includes(keyword) ? '' : 'none';
            });
        });

        function rupiah(n) {
            const x = parseInt(n || 0, 10) || 0;
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function setHidden(el, hide) {
            if (!el) return;
            el.classList[hide ? 'add' : 'remove']('hidden');
        }

        function decodeItemsFromB64(b64) {
            if (!b64) return [];
            try {
                const bin = atob(b64);
                const bytes = new Uint8Array(bin.length);
                for (let i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
                const raw = new TextDecoder('utf-8').decode(bytes);
                return JSON.parse(raw || '[]');
            } catch (e) {
                return [];
            }
        }

        function selectTicket(btn) {
            // highlight selection
            const all = list.querySelectorAll('button[data-ticket-id]');
            all.forEach(x => x.classList.remove('bg-slate-50', 'border-slate-300'));
            btn.classList.add('bg-slate-50', 'border-slate-300');

            const ticketId = btn.dataset.ticketId;
            const txId = btn.dataset.txId;
            const queueNo = btn.dataset.queueNo;
            const ticketNo = btn.dataset.ticketNo;
            const table = btn.dataset.table;
            const cashier = btn.dataset.cashier;
            const status = btn.dataset.status;
            const total = btn.dataset.total;
            const payMethod = btn.dataset.payMethod || '';
            const payRef = btn.dataset.payRef || '';
            const paidAt = btn.dataset.paidAt || '';

            const items = decodeItemsFromB64(btn.dataset.itemsB64 || '');

            // header
            document.getElementById('dQueue').innerText = '#' + String(queueNo).padStart(3, '0');
            document.getElementById('dBill').innerText = ticketNo || '-';
            document.getElementById('dStatus').innerText = (status || '-').toUpperCase();
            document.getElementById('dTable').innerText = table || '-';
            document.getElementById('dCashier').innerText = cashier || '-';
            document.getElementById('dTotal').innerText = rupiah(total);
            document.getElementById('dTotalBottom').innerText = rupiah(total);

            // print links
            document.getElementById('btnPrintKot').setAttribute('href', "{{ url('/print/kot') }}/" + ticketId);

            const btnRcpt = document.getElementById('btnPrintReceipt');
            if (btnRcpt) {
                if (status === 'paid') {
                    btnRcpt.classList.remove('hidden');
                    btnRcpt.setAttribute('href', "{{ url('/print/receipt') }}/" + txId);
                } else {
                    btnRcpt.classList.add('hidden');
                    btnRcpt.setAttribute('href', "#");
                }
            }

            // items render
            const ul = document.getElementById('dItems');
            ul.innerHTML = '';
            if (items.length === 0) {
                ul.innerHTML = '<li class="text-slate-500">Item kosong.</li>';
            } else {
                items.forEach(it => {
                    const note = it.note ? `<div class="text-xs text-slate-500">Note: ${it.note}</div>` : '';
                    ul.insertAdjacentHTML('beforeend', `
          <li class="flex justify-between gap-3">
            <div>
              <div class="font-medium">${it.qty}x ${it.name}</div>
              ${note}
            </div>
            <div class="text-slate-600 whitespace-nowrap">${rupiah(it.subtotal)}</div>
          </li>
        `);
                });
            }

            // payment sections
            const paidBox = document.getElementById('paidBox');
            const unpaidBox = document.getElementById('unpaidBox');
            const emptyPayBox = document.getElementById('emptyPayBox');
            const transferPendingBox = document.getElementById('transferPendingBox');
            const transferMarkBox = document.getElementById('transferMarkBox');

            setHidden(emptyPayBox, true);

            // txId hidden inputs
            const payTxId = document.getElementById('payTxId');
            const verifyTxId = document.getElementById('verifyTxId');
            const markTxId = document.getElementById('markTxId');
            if (payTxId) payTxId.value = txId;
            if (verifyTxId) verifyTxId.value = txId;
            if (markTxId) markTxId.value = txId;

            if (status === 'paid') {
                setHidden(paidBox, false);
                setHidden(unpaidBox, true);
                const meta = document.getElementById('paidMeta');
                if (meta) {
                    meta.innerText = (payMethod ? payMethod : '-') + ' • ' + (paidAt ? paidAt : '-');
                }
            } else {
                setHidden(paidBox, true);
                setHidden(unpaidBox, false);

                if (status === 'pending_verification') {
                    setHidden(transferPendingBox, false);
                    setHidden(transferMarkBox, true);
                    const tr = document.getElementById('transferRef');
                    if (tr) tr.innerText = payRef || '-';
                } else {
                    setHidden(transferPendingBox, true);
                    setHidden(transferMarkBox, false);
                }
            }
        }
    </script>
@endsection
