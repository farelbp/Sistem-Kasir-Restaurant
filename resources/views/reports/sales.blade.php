@extends('layouts.app')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
  <div>
    <div class="text-xl font-bold">Laporan Penjualan</div>
    <div class="text-sm text-slate-500">Range: {{ $from }} s/d {{ $to }}</div>
  </div>

  <form method="GET" class="flex flex-wrap items-center gap-2">
    <input type="date" name="from" value="{{ $from }}" class="rounded-xl border px-3 py-2" />
    <input type="date" name="to" value="{{ $to }}" class="rounded-xl border px-3 py-2" />
    <button class="btn-gold rounded-xl px-4 py-2 font-semibold text-white">Terapkan</button>
  </form>
</div>

<div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Total Transaksi</div>
    <div class="text-2xl font-bold">{{ $summary['count'] }}</div>
  </div>
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Omzet</div>
    <div class="text-2xl font-extrabold" style="color:var(--gold)">Rp {{ number_format($summary['omzet'],0,',','.') }}</div>
  </div>
  <div class="rounded-3xl border bg-white p-4">
    <div class="text-sm text-slate-500">Gross Profit</div>
    <div class="text-2xl font-bold">Rp {{ number_format($gross['profit'],0,',','.') }}</div>
    <div class="text-xs text-slate-500">Revenue {{ number_format($gross['revenue'],0,',','.') }} - COGS {{ number_format($gross['cogs'],0,',','.') }}</div>
  </div>
</div>

<div class="mt-4 rounded-3xl border bg-white p-4">
  <div class="font-semibold mb-2">Daftar Transaksi (Paid)</div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-500">
          <th class="py-2">Paid At</th>
          <th>Bill</th>
          <th>Meja</th>
          <th>Kasir</th>
          <th align="right">Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($txs as $t)
          <tr class="border-t">
            <td class="py-2">{{ $t->paid_at }}</td>
            <td class="font-medium">{{ $t->bill_no }}</td>
            <td>{{ $t->table?->code ?? '-' }}</td>
            <td>{{ $t->cashier?->name }}</td>
            <td align="right">Rp {{ number_format($t->grand_total,0,',','.') }}</td>
            <td class="text-right"><a class="rounded-xl border px-3 py-2 hover:bg-slate-50" href="{{ route('print.receipt',$t->id) }}">Print</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4 rounded-3xl border bg-white p-4">
  <div class="font-semibold mb-2">Rekap Produk</div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-500">
          <th class="py-2">Produk</th>
          <th align="right">Qty</th>
          <th align="right">Omzet</th>
          <th align="right">COGS</th>
          <th align="right">Profit</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
          <tr class="border-t">
            <td class="py-2 font-medium">{{ $it->name }}</td>
            <td align="right">{{ $it->qty }}</td>
            <td align="right">Rp {{ number_format($it->omzet,0,',','.') }}</td>
            <td align="right">Rp {{ number_format($it->cogs,0,',','.') }}</td>
            <td align="right">Rp {{ number_format(($it->omzet - $it->cogs),0,',','.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
