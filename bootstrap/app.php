<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;/
// use Illuminate\Http\Middleware\HandleCors;/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

    )
    ->withMiddleware(function (Middleware $middleware) {
        // グローバルミドルウェア（全リクエスト対象）//追記部分
        // $middleware->append([
        //     HandleCors::class, // ← CORSミドルウェア　
        // ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        //追記部分
        // $middleware->api(prepend: [
        //     EnsureFrontendRequestsAreStateful::class, // ← Sanctumミドルウェア（API前に）
        // ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
