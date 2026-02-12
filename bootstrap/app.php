<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
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

        $exceptions->render(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'DATABASE_ERROR',
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : 'Database operation failed',
                ], 500);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'NOT_FOUND',
                    'message' => 'Resource not found',
                ], 404);
            }
        });

        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'VALIDATION_ERROR',
                    'message' => 'Invalid data provided',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'INTERNAL_SERVER_ERROR',
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong',
                ], 500);
            }
        });
    })->create();
