<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (!in_array(auth()->user()->role, ['admin', 'hype'])) {
            abort(403);
        }

        return $next($request);
    }
}
