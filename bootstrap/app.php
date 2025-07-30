<?php

use App\Http\Middleware\TestMiddleware;
use App\http\Middleware\TestTowMiddeware;
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
        // $middleware->alias([
        //     'test' => TestMiddleware::class,
        //     'test2' => TestTowMiddeware::class,
        // ]);

        $middleware->appendToGroup('group-name', [
            TestMiddleware::class,
            TestTowMiddeware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
