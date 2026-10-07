<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->role;

        if (!in_array($role, ['admin', 'staff'])) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
