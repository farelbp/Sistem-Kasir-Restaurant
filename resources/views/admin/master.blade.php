@extends('layouts.app')
@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- ===================== KATEGORI ===================== --}}
        <div class="rounded-3xl border bg-white p-4">
            <div class="font-semibold mb-3">Kategori</div>

            <form method="POST" action="{{ route('admin.master.category.save') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="text-sm font-medium">Nama Kategori</label>
                    <input name="name" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Contoh: Makanan" />
                </div>

                <div>
                    <label class="text-sm font-medium">Urutan</label>
                    <input name="sort_order" type="number" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="0"
                        value="0" />
                    <p class="mt-1 text-xs text-slate-500">Semakin kecil, semakin atas.</p>
                </div>

                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked> Aktif
                </label>

                <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">Tambah</button>
            </form>


            <div class="mt-4 space-y-2">
                @foreach ($categories as $c)
                    <div class="flex items-center justify-between rounded-2xl border p-3 gap-2">
                        <div>
                            <div class="font-medium">{{ $c->name }}</div>
                            <div class="text-xs text-slate-500">
                                order: {{ $c->sort_order }} •
                                <span class="{{ $c->is_active ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $c->is_active ? 'aktif' : 'nonaktif' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="openDialog('editCat{{ $c->id }}')"
                                class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                Edit
                            </button>

                            <form method="POST" action="{{ route('admin.master.category.toggle', $c->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $c->id }}">
                                <button class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                    {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Edit Kategori --}}
                    <dialog id="editCat{{ $c->id }}" class="rounded-3xl p-0 w-full max-w-md">
                        <div class="bg-white rounded-3xl border shadow-sm p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">Edit Kategori</div>
                                <button type="button" onclick="closeDialog('editCat{{ $c->id }}')"
                                    class="rounded-xl border px-3 py-1.5 text-sm hover:bg-slate-50">✕</button>
                            </div>

                            <form method="POST" action="{{ route('admin.master.category.update', $c->id) }}"
                                class="mt-4 space-y-3">
                                @csrf
                                <input type="hidden" name="id" value="{{ $c->id }}">

                                <div>
                                    <label class="text-sm font-medium">Nama</label>
                                    <input name="name" value="{{ $c->name }}"
                                        class="mt-1 w-full rounded-xl border px-3 py-2" />
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Urutan</label>
                                    <input name="sort_order" type="number" value="{{ $c->sort_order }}"
                                        class="mt-1 w-full rounded-xl border px-3 py-2" />
                                </div>

                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ $c->is_active ? 'checked' : '' }}> Aktif
                                </label>

                                <div class="flex gap-2 pt-2">
                                    <button class="btn-gold flex-1 rounded-xl px-4 py-2 font-semibold text-white">
                                        Simpan
                                    </button>
                                    <button type="button" onclick="closeDialog('editCat{{ $c->id }}')"
                                        class="flex-1 rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endforeach
            </div>
        </div>

        {{-- ===================== PRODUK ===================== --}}
        <div class="rounded-3xl border bg-white p-4">
            <div class="font-semibold mb-3">Produk</div>

            <form method="POST" action="{{ route('admin.master.product.save') }}" enctype="multipart/form-data"
                class="space-y-3">
                @csrf

                <div>
                    <label class="text-sm font-medium">Kategori</label>
                    <select name="category_id" class="mt-1 w-full rounded-xl border px-3 py-2">
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Nama Produk</label>
                    <input name="name" class="mt-1 w-full rounded-xl border px-3 py-2"
                        placeholder="Contoh: Nasi Goreng" />
                </div>
                <div>
                    <label class="text-sm font-medium">Gambar Menu</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 w-full rounded-xl border px-3 py-2 bg-white" />
                    <p class="mt-1 text-xs text-slate-500">JPG/PNG/WebP, maksimal 2MB.</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm font-medium">Harga</label>
                        <input name="price" type="number" class="mt-1 w-full rounded-xl border px-3 py-2"
                            placeholder="0" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">HPP</label>
                        <input name="cost" type="number" class="mt-1 w-full rounded-xl border px-3 py-2"
                            placeholder="0" value="0" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 items-end">
                    <label class="inline-flex items-center gap-2 text-sm pb-2">
                        <input type="hidden" name="stock_enabled" value="0">
                        <input type="checkbox" name="stock_enabled" value="1"> Stock enabled
                    </label>

                    <div>
                        <label class="text-sm font-medium">Stock Qty</label>
                        <input name="stock_qty" type="number" class="mt-1 w-full rounded-xl border px-3 py-2"
                            placeholder="0" value="0" />
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked> Aktif
                </label>

                <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">Tambah</button>
            </form>

            <div class="mt-4 overflow-y-auto max-h-[520px] space-y-2">
                @foreach ($products as $p)
                    <div class="rounded-2xl border p-3 flex items-start justify-between gap-2">
                        <div>
                            <div class="font-medium">{{ $p->name }}</div>
                            <div class="text-xs text-slate-500">
                                {{ $p->category?->name }} • Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                @if ($p->stock_enabled)
                                    Stock: {{ $p->stock_qty }} •
                                @endif
                                <span class="{{ $p->is_active ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $p->is_active ? 'aktif' : 'nonaktif' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="openDialog('editProd{{ $p->id }}')"
                                class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                Edit
                            </button>

                            <form method="POST" action="{{ route('admin.master.product.toggle', $p->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $p->id }}">
                                <button class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                    {{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Edit Produk --}}
                    <dialog id="editProd{{ $p->id }}" class="rounded-3xl p-0 w-full max-w-xl">
                        <div class="bg-white rounded-3xl border shadow-sm p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">Edit Produk</div>
                                <button type="button" onclick="closeDialog('editProd{{ $p->id }}')"
                                    class="rounded-xl border px-3 py-1.5 text-sm hover:bg-slate-50">✕</button>
                            </div>

                            <form method="POST" action="{{ route('admin.master.product.update', $p->id) }}"
                                class="mt-4 space-y-3" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $p->id }}">

                                <div>
                                    <label class="text-sm font-medium">Kategori</label>
                                    <select name="category_id" class="mt-1 w-full rounded-xl border px-3 py-2">
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}" @selected($p->category_id == $c->id)>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Nama Produk</label>
                                    <input name="name" value="{{ $p->name }}"
                                        class="mt-1 w-full rounded-xl border px-3 py-2" />
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Gambar Menu</label>

                                    @if ($p->image_path)
                                        <div class="mt-2 flex items-center gap-3">
                                            <img src="{{ asset('storage/' . $p->image_path) }}"
                                                class="h-16 w-16 rounded-2xl object-cover border"
                                                alt="{{ $p->name }}">
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="hidden" name="remove_image" value="0">
                                                <input type="checkbox" name="remove_image" value="1">
                                                Hapus gambar
                                            </label>
                                        </div>
                                    @endif

                                    <input type="file" name="image" accept="image/*"
                                        class="mt-2 w-full rounded-xl border px-3 py-2 bg-white" />
                                    <p class="mt-1 text-xs text-slate-500">Upload untuk mengganti gambar.</p>
                                </div>


                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-sm font-medium">Harga</label>
                                        <input name="price" type="number" value="{{ $p->price }}"
                                            class="mt-1 w-full rounded-xl border px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">HPP</label>
                                        <input name="cost" type="number" value="{{ $p->cost }}"
                                            class="mt-1 w-full rounded-xl border px-3 py-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 items-center">
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="stock_enabled" @checked($p->stock_enabled)>
                                        Stock enabled
                                    </label>
                                    <div>
                                        <label class="text-sm font-medium">Stock Qty</label>
                                        <input name="stock_qty" type="number" value="{{ $p->stock_qty }}"
                                            class="mt-1 w-full rounded-xl border px-3 py-2" />
                                    </div>
                                </div>

                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ $p->is_active ? 'checked' : '' }}> Aktif
                                </label>

                                <div class="flex gap-2 pt-2">
                                    <button class="btn-gold flex-1 rounded-xl px-4 py-2 font-semibold text-white">
                                        Simpan
                                    </button>
                                    <button type="button" onclick="closeDialog('editProd{{ $p->id }}')"
                                        class="flex-1 rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endforeach
            </div>
        </div>

        {{-- ===================== MEJA ===================== --}}
        <div class="rounded-3xl border bg-white p-4">
            <div class="font-semibold mb-3">Meja</div>

            <form method="POST" action="{{ route('admin.master.table.save') }}" class="space-y-3">
                @csrf

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm font-medium">Code</label>
                        <input name="code" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="T01" />
                    </div>

                    <div>
                        <label class="text-sm font-medium">Nama Meja</label>
                        <input name="name" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Meja 1" />
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked> Aktif
                </label>

                <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">Tambah</button>
            </form>


            <div class="mt-4 space-y-2">
                @foreach ($tables as $t)
                    <div class="rounded-2xl border p-3 flex items-center justify-between gap-2">
                        <div>
                            <div class="font-medium">{{ $t->code }} - {{ $t->name }}</div>
                            <div class="text-xs text-slate-500">
                                <span class="{{ $t->is_active ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $t->is_active ? 'aktif' : 'nonaktif' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="openDialog('editTable{{ $t->id }}')"
                                class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                Edit
                            </button>

                            <form method="POST" action="{{ route('admin.master.table.toggle', $t->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $t->id }}">
                                <button class="rounded-xl border px-3 py-2 text-sm hover:bg-slate-50">
                                    {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Edit Meja --}}
                    <dialog id="editTable{{ $t->id }}" class="rounded-3xl p-0 w-full max-w-md">
                        <div class="bg-white rounded-3xl border shadow-sm p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">Edit Meja</div>
                                <button type="button" onclick="closeDialog('editTable{{ $t->id }}')"
                                    class="rounded-xl border px-3 py-1.5 text-sm hover:bg-slate-50">✕</button>
                            </div>

                            <form method="POST" action="{{ route('admin.master.table.update', $t->id) }}"
                                class="mt-4 space-y-3">
                                @csrf
                                <input type="hidden" name="id" value="{{ $t->id }}">

                                <div>
                                    <label class="text-sm font-medium">Code</label>
                                    <input name="code" value="{{ $t->code }}"
                                        class="mt-1 w-full rounded-xl border px-3 py-2" />
                                </div>

                                <div>
                                    <label class="text-sm font-medium">Nama</label>
                                    <input name="name" value="{{ $t->name }}"
                                        class="mt-1 w-full rounded-xl border px-3 py-2" />
                                </div>

                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ $t->is_active ? 'checked' : '' }}> Aktif
                                </label>

                                <div class="flex gap-2 pt-2">
                                    <button class="btn-gold flex-1 rounded-xl px-4 py-2 font-semibold text-white">
                                        Simpan
                                    </button>
                                    <button type="button" onclick="closeDialog('editTable{{ $t->id }}')"
                                        class="flex-1 rounded-xl border px-4 py-2 font-semibold hover:bg-slate-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        function openDialog(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.showModal();
        }

        function closeDialog(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.close();
        }

        // close modal jika klik area luar dialog
        document.addEventListener('click', function(e) {
            const dialog = e.target;
            if (dialog && dialog.tagName === 'DIALOG') {
                const rect = dialog.getBoundingClientRect();
                const inside = rect.top <= e.clientY && e.clientY <= rect.bottom &&
                    rect.left <= e.clientX && e.clientX <= rect.right;
                if (!inside) dialog.close();
            }
        });
    </script>
@endsection
