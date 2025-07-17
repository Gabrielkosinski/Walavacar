<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 🚦 Rate limiting para APIs apenas
        $middleware->throttleApi();
        
        // 🔒 Middleware de segurança desabilitado temporariamente para debug Railway
        // $middleware->web(append: [
        //     \App\Http\Middleware\SecurityHeaders::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
