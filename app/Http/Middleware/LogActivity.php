<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::guard('admin')->check() && Auth::user()->hasRole('admin')) {
            // Aksi jika pengguna yang login memiliki role 'admin'

            $activity = sprintf('%s accessed %s', Auth::user()->name, $request->path());
            ActivityLog::create([
                'admin_id' => Auth::id(),
                'activity' => $activity,
            ]);
        }

        return $response;
    }
}

