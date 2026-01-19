@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Tanggal</div>
    <div class="text-xl font-bold">{{ $today }}</div>
  </div>
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Omzet Hari Ini</div>
    <div class="text-xl font-extrabold" style="color:var(--gold)">Rp {{ number_format($omzet,0,',','.') }}</div>
  </div>
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Transaksi Paid</div>
    <div class="text-xl font-bold">{{ $paidCount }}</div>
  </div>
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Pending Verifikasi</div>
    <div class="text-xl font-bold">{{ $pendingCount }}</div>
  </div>
</div>

<div class="mt-4 rounded-3xl border bg-white p-4">
  <div class="font-semibold">Low Stock (indikatif)</div>
  <div class="mt-3 overflow-x-auto">
    <table class="w-full text-sm">
      <thead><tr class="text-left text-slate-500"><th class="py-2">Produk</th><th>Stock</th><th>Enabled</th></tr></thead>
      <tbody>
        @foreach($lowStock as $p)
          <tr class="border-t"><td class="py-2 font-medium">{{ $p->name }}</td><td>{{ $p->stock_qty }}</td><td>{{ $p->stock_enabled ? 'YES' : 'NO' }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
