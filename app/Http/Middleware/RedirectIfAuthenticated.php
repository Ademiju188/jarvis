<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->role === 'admin') {
                return redirect('/dashboard');
            } else if ($user->role === 'headteacher') {
                return redirect('/headteacher/dashboard');
            }
        }
        return $next($request);
    }
}
