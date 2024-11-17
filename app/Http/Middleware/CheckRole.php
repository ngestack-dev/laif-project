<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah pengguna memiliki salah satu role yang diperlukan
        if (!$request->user()->hasAnyRole($roles)) {
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}


