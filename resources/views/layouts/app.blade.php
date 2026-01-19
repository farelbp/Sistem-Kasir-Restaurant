<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Astabrata Restaurant POS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --gold: #D4AF37;
            --gold-dark: #B8921F;
            --bg: #FFFDF7;
        }

        body {
            background: var(--bg);
        }

        .btn-gold {
            background: var(--gold);
        }

        .btn-gold:hover {
            background: var(--gold-dark);
        }
    </style>
</head>

<body class="text-slate-800">

    @if (!empty($me))
        <header class="sticky top-0 z-30 border-b bg-white/80 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl border flex items-center justify-center"
                        style="border-color:var(--gold)">
                        <span class="font-bold" style="color:var(--gold)">A</span>
                    </div>
                    <div>
                        <div class="font-semibold">Astabrata Restaurant</div>
                        <div class="text-xs text-slate-500">POS Internal</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-600">
                        {{ $me->name }} • <span class="font-medium">{{ strtoupper($me->role) }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">Logout</button>
                    </form>
                </div>
            </div>

            <nav class="mx-auto max-w-7xl px-4 pb-3 flex gap-2 text-sm">
                @if ($me->role === 'kasir')
                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('kasir/pos') ? 'bg-slate-100' : '' }}"
                        href="{{ route('kasir.pos') }}">POS</a>

                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('kasir/kitchen-today') ? 'bg-slate-100' : '' }}"
                        href="{{ route('kasir.kitchen_today') }}">Captain Order Today</a>
                @endif

                @if ($me->role === 'admin')
                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('admin/dashboard') ? 'bg-slate-100' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>

                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('admin/users') ? 'bg-slate-100' : '' }}"
                        href="{{ route('admin.users.index') }}">Users</a>


                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('admin/master') ? 'bg-slate-100' : '' }}"
                        href="{{ route('admin.master.index') }}">Master</a>

                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('admin/reports/sales') ? 'bg-slate-100' : '' }}"
                        href="{{ route('admin.reports.sales') }}">Report</a>

                    <a class="rounded-xl px-3 py-2 hover:bg-slate-50 {{ request()->is('admin/stocks') ? 'bg-slate-100' : '' }}"
                        href="{{ route('admin.stocks') }}">Stock</a>
                @endif
            </nav>
        </header>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-6">
        @if ($errors->any())
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4">
                <div class="font-semibold text-red-700">Terjadi kesalahan</div>
                <ul class="mt-2 list-disc pl-6 text-sm text-red-700">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>

</html>
