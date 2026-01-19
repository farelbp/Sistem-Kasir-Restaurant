<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        $uid = $request->session()->get('auth_user_id');
        if (!$uid) {
            return redirect()->route('login');
        }

        $me = User::find($uid);
        if (!$me || !$me->is_active) {
            $request->session()->forget('auth_user_id');
            return redirect()->route('login');
        }

        // share to views
        view()->share('me', $me);

        return $next($request);
    }
}
