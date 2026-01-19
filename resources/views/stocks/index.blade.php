@extends('layouts.app')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
  <div>
    <div class="text-xl font-bold">Stock</div>
    <div class="text-sm text-slate-500">Restock / Adjust / Histori</div>
  </div>
</div>

<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">

  <div class="rounded-3xl border bg-white p-4">
    <div class="font-semibold mb-3">Restock</div>
    <form method="POST" action="{{ route('admin.stocks.restock') }}" class="space-y-2">
      @csrf
      <select name="product_id" class="w-full rounded-xl border px-3 py-2">
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock_enabled ? 'stock:' . $p->stock_qty : 'stock off' }})</option>
        @endforeach
      </select>
      <div class="grid grid-cols-2 gap-2">
        <input name="qty" type="number" class="w-full rounded-xl border px-3 py-2" placeholder="Qty" />
        <input name="unit_cost" type="number" class="w-full rounded-xl border px-3 py-2" placeholder="HPP (opsional)" />
      </div>
      <input name="supplier_name" class="w-full rounded-xl border px-3 py-2" placeholder="Supplier (opsional)" />
      <input name="note" class="w-full rounded-xl border px-3 py-2" placeholder="Catatan (opsional)" />
      <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">Simpan Restock</button>
      <div class="text-xs text-slate-500">*Stock hanya berubah jika product.stock_enabled = true.</div>
    </form>
  </div>

  <div class="rounded-3xl border bg-white p-4">
    <div class="font-semibold mb-3">Adjust</div>
    <form method="POST" action="{{ route('admin.stocks.adjust') }}" class="space-y-2">
      @csrf
      <select name="product_id" class="w-full rounded-xl border px-3 py-2">
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock_enabled ? 'stock:' . $p->stock_qty : 'stock off' }})</option>
        @endforeach
      </select>
      <input name="qty" type="number" class="w-full rounded-xl border px-3 py-2" placeholder="Qty (+ / -)" />
      <input name="note" class="w-full rounded-xl border px-3 py-2" placeholder="Alasan adjust" />
      <button class="w-full rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">Simpan Adjust</button>
    </form>
  </div>

  <div class="rounded-3xl border bg-white p-4">
    <div class="font-semibold mb-3">Stok Saat Ini</div>
    <div class="overflow-y-auto max-h-[520px]">
      <table class="w-full text-sm">
        <thead><tr class="text-left text-slate-500"><th class="py-2">Produk</th><th align="right">Stock</th><th>Enabled</th></tr></thead>
        <tbody>
          @foreach($products as $p)
            <tr class="border-t">
              <td class="py-2 font-medium">{{ $p->name }}</td>
              <td align="right">{{ $p->stock_enabled ? $p->stock_qty : '-' }}</td>
              <td>{{ $p->stock_enabled ? 'YES' : 'NO' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-4 rounded-3xl border bg-white p-4">
  <div class="font-semibold mb-2">Histori Stock Movements (200 terakhir)</div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-500">
          <th class="py-2">Waktu</th>
          <th>Produk</th>
          <th>Type</th>
          <th align="right">Qty</th>
          <th>Ref</th>
          <th>Note</th>
        </tr>
      </thead>
      <tbody>
        @foreach($movements as $m)
          <tr class="border-t">
            <td class="py-2">{{ $m->created_at }}</td>
            <td class="font-medium">{{ $m->product?->name }}</td>
            <td>{{ strtoupper($m->type) }}</td>
            <td align="right">{{ $m->qty }}</td>
            <td>{{ $m->ref_type }} {{ $m->ref_id }}</td>
            <td>{{ $m->note }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
