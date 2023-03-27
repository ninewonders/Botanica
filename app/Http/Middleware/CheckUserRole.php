<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->admin == 1) {
            return $next($request);
        }

        return redirect('/');
    }
}
