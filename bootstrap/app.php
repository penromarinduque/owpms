<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureHasUserType;
use App\Http\Middleware\EnsureHasPermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'userType' => EnsureHasUserType::class,
            'permission' => EnsureHasPermission::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
