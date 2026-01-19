<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderByDesc('id')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'username' => ['required', 'string', 'max:40', 'alpha_dash', 'unique:users,username'],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['required', 'string', 'min:4', 'max:100'],
            'is_active' => ['nullable'],
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:80'],
            'username' => ['required', 'string', 'max:40', 'alpha_dash', Rule::unique('users', 'username')->ignore($request->id)],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => ['nullable', 'string', 'min:4', 'max:100'],
            'is_active' => ['nullable'],
        ]);

        $u = User::findOrFail($data['id']);

        $u->name = $data['name'];
        $u->username = $data['username'];
        $u->role = $data['role'];
        $u->is_active = $request->boolean('is_active');

        if (!empty($data['password'])) {
            $u->password = Hash::make($data['password']);
        }

        $u->save();

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $u = User::findOrFail($data['id']);

        // jangan biarkan admin mematikan dirinya sendiri (biar gak ke-lock)
        $me = $request->user();
        if ($me && (int)$me->id === (int)$u->id) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun yang sedang dipakai login.');
        }

        $u->is_active = !$u->is_active;
        $u->save();

        return back()->with('success', 'Status user diubah.');
    }
}
