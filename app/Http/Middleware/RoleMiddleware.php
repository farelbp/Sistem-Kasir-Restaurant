<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $uid = $request->session()->get('auth_user_id');
        $me = $uid ? User::find($uid) : null;
        if (!$me) {
            return redirect()->route('login');
        }

        if (!in_array($me->role, $roles, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
