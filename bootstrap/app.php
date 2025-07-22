<?php

use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => url('/'));
        $middleware->redirectUsersTo(function () {
            if (Auth::user()->role === 'admin') {
                return url('/dashboard');
            }
            return url('/headteacher/dashboard');
        });
        $middleware->alias([
            'admin.only' => AdminOnly::class,
            'redirect.if.authenticated' => RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
