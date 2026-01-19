@extends('layouts.app')
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Manajemen User</div>
            <div class="text-sm text-slate-500">Buat akun kasir/admin, edit, dan nonaktifkan akun.</div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- FORM TAMBAH --}}
        <div class="rounded-3xl border bg-white p-4">
            <div class="font-semibold mb-3">Tambah User</div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-2">
                @csrf

                <input name="name" class="w-full rounded-xl border px-3 py-2" placeholder="Nama" required>

                <input name="username" class="w-full rounded-xl border px-3 py-2" placeholder="Username (tanpa spasi)"
                    required>

                <select name="role" class="w-full rounded-xl border px-3 py-2" required>
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>

                <input name="password" type="password" class="w-full rounded-xl border px-3 py-2" placeholder="Password"
                    required>

                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" checked> Aktif
                </label>

                <button class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">
                    Simpan User
                </button>
            </form>

            <div class="mt-3 text-xs text-slate-500">
                Tips: untuk reset password user lama, pakai tombol Edit lalu isi password baru.
            </div>
        </div>

        {{-- LIST USER --}}
        <div class="lg:col-span-2 rounded-3xl border bg-white p-4">
            <div class="flex items-center justify-between gap-2">
                <div class="font-semibold">Daftar User</div>
                <div class="text-xs text-slate-500">Total: {{ $users->count() }}</div>
            </div>

            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-2 pr-3">Nama</th>
                            <th class="py-2 pr-3">Username</th>
                            <th class="py-2 pr-3">Role</th>
                            <th class="py-2 pr-3">Status</th>
                            <th class="py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr class="border-b">
                                <td class="py-3 pr-3 font-medium">{{ $u->name }}</td>
                                <td class="py-3 pr-3">{{ $u->username }}</td>
                                <td class="py-3 pr-3">
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs">
                                        {{ strtoupper($u->role) }}
                                    </span>
                                </td>
                                <td class="py-3 pr-3">
                                    <span
                                        class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs
                  {{ $u->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                                        {{ $u->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="inline-flex gap-2">
                                        <button type="button" class="rounded-xl border px-3 py-2 hover:bg-slate-50"
                                            onclick="openDialog('editUser{{ $u->id }}')">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.users.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $u->id }}">
                                            <button class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>

                                    {{-- MODAL EDIT --}}
                                    {{-- MODAL EDIT --}}
                                    <dialog id="editUser{{ $u->id }}" class="rounded-3xl p-0 w-full max-w-lg">
                                        <div class="rounded-3xl border bg-white p-5 text-left">
                                            <div class="flex items-center justify-between gap-2">
                                                <div>
                                                    <div class="text-lg font-bold">Edit User</div>
                                                    <div class="text-xs text-slate-500">Ubah data / reset password</div>
                                                </div>
                                                <button type="button" class="rounded-xl border px-3 py-2 hover:bg-slate-50"
                                                    onclick="closeDialog('editUser{{ $u->id }}')">
                                                    Tutup
                                                </button>
                                            </div>

                                            <form method="POST" action="{{ route('admin.users.update') }}"
                                                class="mt-4 space-y-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $u->id }}">

                                                <div>
                                                    <label class="text-sm font-medium">Nama</label>
                                                    <input name="name" class="mt-1 w-full rounded-xl border px-3 py-2"
                                                        value="{{ $u->name }}" required>
                                                </div>

                                                <div>
                                                    <label class="text-sm font-medium">Username</label>
                                                    <input name="username" class="mt-1 w-full rounded-xl border px-3 py-2"
                                                        value="{{ $u->username }}" required>
                                                </div>

                                                <div>
                                                    <label class="text-sm font-medium">Role</label>
                                                    <select name="role" class="mt-1 w-full rounded-xl border px-3 py-2"
                                                        required>
                                                        <option value="kasir" @selected($u->role === 'kasir')>Kasir</option>
                                                        <option value="admin" @selected($u->role === 'admin')>Admin</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="text-sm font-medium">Password Baru (opsional)</label>
                                                    <input name="password" type="password"
                                                        class="mt-1 w-full rounded-xl border px-3 py-2"
                                                        placeholder="Kosongkan jika tidak diubah">
                                                </div>

                                                <label class="inline-flex items-center gap-2 text-sm pt-1">
                                                    <input type="checkbox" name="is_active" @checked($u->is_active)>
                                                    Aktif
                                                </label>

                                                <button
                                                    class="btn-gold w-full rounded-xl px-4 py-2 font-semibold text-white">
                                                    Simpan Perubahan
                                                </button>
                                            </form>
                                        </div>
                                    </dialog>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function openDialog(id) {
            document.getElementById(id)?.showModal();
        }

        function closeDialog(id) {
            document.getElementById(id)?.close();
        }
    </script>
@endsection
