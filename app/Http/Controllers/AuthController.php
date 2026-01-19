<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $u = User::where('username', $data['username'])->first();
        if (!$u || !$u->is_active || !Hash::check($data['password'], $u->password)) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }

        $request->session()->put('auth_user_id', $u->id);

        return $u->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('kasir.pos');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('auth_user_id');
        return redirect()->route('login');
    }
}
