@extends('layouts.app')
@section('content')
<div class="min-h-[60vh] grid place-items-center">
  <div class="w-full max-w-md rounded-3xl border bg-white p-6 shadow-sm">
    <div class="mb-5">
      <div class="text-2xl font-bold" style="color:var(--gold)">Astabrata Restaurant</div>
      <div class="text-sm text-slate-500">Login Kasir / Admin</div>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
      @csrf
      <div>
        <label class="text-sm font-medium">Username</label>
        <input name="username" value="{{ old('username') }}" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="username">
      </div>
      <div>
        <label class="text-sm font-medium">Password</label>
        <input type="password" name="password" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="password">
      </div>
      <button class="btn-gold w-full rounded-xl px-4 py-3 font-semibold text-white">Masuk</button>
      <div class="text-xs text-slate-500">Default: admin/admin123 • kasir/kasir123</div>
    </form>
  </div>
</div>
@endsection
