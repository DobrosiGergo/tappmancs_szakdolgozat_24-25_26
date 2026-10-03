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
    ->withMiddleware(function (Middleware $middleware) {
        // Serverless hosting puts the app behind a platform proxy that
        // terminates TLS, so the scheme only survives in X-Forwarded-Proto.
        // Without this, url()/route()/asset() emit http:// on an https site
        // and post-login redirects downgrade. The proxy IPs are not a fixed
        // range and the origin is not reachable except through it, so the
        // trusted set is the wildcard.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
