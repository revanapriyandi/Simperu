<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'telegram.linked' => \App\Http\Middleware\CheckTelegramLinked::class,
            'optimize.memory' => \App\Http\Middleware\OptimizeMemoryUsage::class,
        ]);

        // Apply memory optimization to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\OptimizeMemoryUsage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
