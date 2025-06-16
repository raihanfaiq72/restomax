<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

use App\Http\Middleware\CashMid;
use App\Http\Middleware\KitStafMid;
use App\Http\Middleware\ManMid;
use App\Http\Middleware\OwnerMid;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'owner' => \App\Http\Middleware\OwnerMid::class,
            'manager'=> \App\Http\Middleware\ManMid::class,
            'kitchen' => \App\Http\Middleware\KitStafMid::class,
            'cashier'   => \App\Http\Middleware\CashMid::class
        ]);

        $middleware->appendToGroup('api', [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not authenticated'
                ], 401);
            }
            return $e->render($request);
        });
    })
    ->withEvents([ 
        'discover' => true,
    ])->create();
