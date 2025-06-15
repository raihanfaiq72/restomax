<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'owner' => OwnerMid::class,
            'manager'=> ManMid::class,
            'Kitchen Staff'=> KitStafMid::class,
            'cachier'   => CashMid::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withEvents([ 
        'discover' => true,
    ])->create();
