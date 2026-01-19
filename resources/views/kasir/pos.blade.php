@extends('layouts.app')
@section('content')
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('kasir.pos') }}" class="flex items-center gap-2">
                <select name="table_id" class="rounded-xl border px-3 py-2" onchange="this.form.submit()">
                    <option value="">Pilih Meja...</option>
                    @foreach ($tables as $t)
                        <option value="{{ $t->id }}" @selected($tableId == $t->id)>{{ $t->code }} -
                            {{ $t->name }}</option>
                    @endforeach
                </select>

                <select name="category_id" class="rounded-xl border px-3 py-2" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected((int) request('category_id') === $c->id)>{{ $c->name }}
                        </option>
                    @endforeach
                </select>

                <input name="q" value="{{ request('q') }}" class="rounded-xl border px-3 py-2"
                    placeholder="Cari menu..." />

                <button class="rounded-xl border px-3 py-2 hover:bg-slate-50">Filter</button>
            </form>

            <div class="text-sm text-slate-600">
                @if ($tx)
                    Draft: <span class="font-semibold">#{{ $tx->id }}</span>
                @else
                    <span class="text-amber-700">Pilih meja dulu untuk mulai order</span>
                @endif
            </div>
        </div>

        {{-- MENU --}}
        <div class="col-span-8">
            <div class="rounded-3xl border bg-white p-4">
                <div class="mb-3 flex items-center justify-between">
                    <div class="font-semibold">Menu</div>
                    <div class="text-xs text-slate-500">Klik item untuk menambah ke keranjang</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 auto-rows-fr">
                    @foreach ($products as $p)
                        @php
                            $needsTable = !$tx;

                            $stockEnabled = (bool) $p->stock_enabled;
                            $stockQty = (int) ($p->stock_qty ?? 0);

                            // qty produk ini yang sudah ada di keranjang transaksi saat ini
                            $inCartQty = 0;
                            if ($tx && $tx->relationLoaded('items')) {
                                $inCartQty = (int) $tx->items->where('product_id', $p->id)->sum('qty');
                            } elseif ($tx) {
                                // fallback kalau items belum eager load
                                $inCartQty = (int) $tx->items()->where('product_id', $p->id)->sum('qty');
                            }

                            // sisa yang ditampilkan = stok fisik - yang sudah di keranjang
                            $remaining = $stockEnabled ? max(0, $stockQty - $inCartQty) : null;

                            $outOfStock = $stockEnabled && $remaining <= 0;

                            // disable kalau belum pilih meja ATAU stok habis ATAU produk nonaktif
                            $disabled = $needsTable || $outOfStock || !(bool) $p->is_active;
                        @endphp

                        <form method="POST" action="{{ route('kasir.cart.add') }}" class="h-full">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $tx?->id }}" />
                            <input type="hidden" name="product_id" value="{{ $p->id }}" />

                            <button type="submit" @disabled($disabled)
                                class="relative h-full w-full overflow-hidden text-left rounded-3xl border bg-white
               transition hover:shadow-lg active:scale-[0.99] touch-manipulation
               disabled:opacity-50 disabled:cursor-not-allowed min-h-[320px]">

                                <div class="relative aspect-[16/11] w-full bg-slate-50">
                                    @if ($p->image_url)
                                        <img src="{{ asset('storage/' . $p->image_url) }}" alt="{{ $p->name }}"
                                            class="absolute inset-0 h-full w-full object-cover" loading="lazy" />
                                    @else
                                        <div
                                            class="absolute inset-0 flex items-center justify-center text-sm text-slate-400">
                                            No Image
                                        </div>
                                    @endif

                                    <div
                                        class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/65 to-transparent">
                                    </div>

                                    <div class="absolute bottom-3 left-4 right-4">
                                        <div class="text-base font-semibold text-white truncate">{{ $p->name }}</div>
                                        <div class="text-sm text-white/80 truncate">{{ $p->category?->name }}</div>
                                    </div>

                                    <div class="absolute top-4 right-4 flex flex-col items-end gap-2">
                                        <div class="rounded-full bg-white/95 border px-4 py-2 text-sm font-semibold">
                                            + Tambah
                                        </div>
                                    </div>

                                    {{-- Overlay --}}
                                    @if ($needsTable)
                                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                            <span
                                                class="rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                                                Pilih meja dulu
                                            </span>
                                        </div>
                                    @elseif ($outOfStock)
                                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                            <span
                                                class="rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                                                Stok habis
                                            </span>
                                        </div>
                                    @elseif (!(bool) $p->is_active)
                                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                            <span
                                                class="rounded-full border bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                                                Nonaktif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5 flex flex-col gap-3">
                                    <div class="min-w-0">
                                        <div class="text-base font-semibold text-slate-900">{{ $p->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $p->category?->name }}</div>
                                    </div>

                                    <div class="mt-auto flex items-end justify-between gap-3">
                                        <div class="text-2xl font-extrabold leading-none" style="color:var(--gold)">
                                            Rp {{ number_format($p->price, 0, ',', '.') }}
                                        </div>

                                        @if ($stockEnabled)
                                            <div
                                                class="rounded-full bg-black/55 text-white text-xs px-3 py-1 border border-white/20">
                                                Sisa: {{ $remaining }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </button>
                        </form>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- CART --}}
        <div class="col-span-4">
            <div class="rounded-3xl border bg-white p-4 sticky top-28">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Keranjang</div>
                    @if ($tx && $tx->table)
                        <span class="text-xs rounded-full border px-2 py-1">{{ $tx->table->code }}</span>
                    @endif
                </div>

                @if (!$tx)
                    <div class="mt-4 text-sm text-slate-500">Pilih meja untuk mulai transaksi.</div>
                @else
                    <div class="mt-3 space-y-3">
                        @forelse($tx->items as $it)
                            <div class="rounded-2xl border p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="text-sm font-semibold">{{ $it->product_name }}</div>
                                        <div class="text-xs text-slate-500">Rp
                                            {{ number_format($it->unit_price, 0, ',', '.') }}</div>
                                    </div>
                                    <form method="POST" action="{{ route('kasir.cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $it->id }}" />
                                        <button class="text-xs rounded-xl border px-2 py-1 hover:bg-slate-50">Hapus</button>
                                    </form>
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    <form method="POST" action="{{ route('kasir.cart.qty') }}"
                                        class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $it->id }}" />
                                        <input type="number" min="1" name="qty" value="{{ $it->qty }}"
                                            class="w-20 rounded-xl border px-2 py-1" />
                                        <button class="rounded-xl border px-2 py-1 text-xs hover:bg-slate-50">Set</button>
                                    </form>
                                    <div class="ml-auto text-sm font-bold">Rp
                                        {{ number_format($it->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('kasir.cart.note') }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $it->id }}" />
                                    <input name="note" value="{{ $it->note }}"
                                        class="w-full rounded-xl border px-2 py-2 text-sm"
                                        placeholder="Catatan dapur (opsional)" />
                                </form>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">Keranjang masih kosong.</div>
                        @endforelse
                    </div>

                    <div class="mt-4 border-t pt-3 space-y-2">
                        <div class="flex justify-between text-sm"><span>Subtotal</span><span>Rp
                                {{ number_format($tx->subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-base font-bold"><span>Total</span><span
                                style="color:var(--gold)">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('kasir.send') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $tx->id }}" />
                        <button class="btn-gold w-full rounded-xl px-4 py-3 font-semibold text-white"
                            @disabled($tx->items->count() == 0)>
                            Kirim ke Dapur & Print KOT
                        </button>
                    </form>

                    <div class="mt-3 text-xs text-slate-500">Pembayaran dilakukan setelah KOT tercetak.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
