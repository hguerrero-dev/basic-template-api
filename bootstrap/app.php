<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'FORBIDDEN',
                    'message' => 'You do not have permission to perform this action',
                ], 403);
            }
        });

        $exceptions->render(function (UnauthorizedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'UNAUTHORIZED',
                    'message' => 'Authentication required',
                ], 401);
            }
        });
    })->create();
