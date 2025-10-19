<?php

use App\Http\Middleware\HandleNotFound;
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
        $middleware->redirectUsersTo(fn () => route('posts'));
        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->append(HandleNotFound::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
