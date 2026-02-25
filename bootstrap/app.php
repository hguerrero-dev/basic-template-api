<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        // 1. Errores de Autenticación (401)
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'UNAUTHORIZED',
                    'message' => 'Authentication required. Please provide valid credentials.',
                ], 401);
            }
        });

        // 2. Errores de Permisos (403)
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'FORBIDDEN',
                    'message' => 'You do not have permission to access this resource.',
                ], 403);
            }
        });

        // 3. Recurso no encontrado (404)
        // Laravel a veces envuelve ModelNotFoundException en NotFoundHttpException,
        // por lo que atrapamos ambas aquí.
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                // Mensaje personalizado si es por modelo no encontrado
                $message = 'Route or resource not found.';
                if ($e->getPrevious() instanceof ModelNotFoundException) {
                    $message = 'The requested resource was not found in the database.';
                }

                return response()->json([
                    'error' => 'NOT_FOUND',
                    'message' => $message,
                ], 404);
            }
        });

        // Hacemos explícito el ModelNotFoundException también por seguridad
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'NOT_FOUND',
                    'message' => 'The requested resource was not found.',
                ], 404);
            }
        });

        // 4. Método HTTP no permitido (405)
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'METHOD_NOT_ALLOWED',
                    'message' => ' The ' . $request->method() . ' method is not supported for this route.',
                ], 405);
            }
        });

        // 5. Errores de Validación (422)
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'VALIDATION_ERROR',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // 6. Errores de Base de Datos (500) - Útil para debugging, ocultar en prod
        $exceptions->render(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'DATABASE_ERROR',
                    'message' => config('app.debug') ? $e->getMessage() : 'Database operation failed.',
                ], 500);
            }
        });

        // 7. Handler Genérico (500) - CATCH ALL
        // Es importante verificar que NO sea una de las excepciones HTTP válidas de Symfony
        // para no sobrescribir códigos 4xx con 500.
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                // Si es una excepción HTTP válida (ej. 404, 403, 419), dejamos que Laravel la maneje
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    return null;
                }

                return response()->json([
                    'error' => 'INTERNAL_SERVER_ERROR',
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : 'An unexpected error occurred. Please try again later.',
                    'file'    => config('app.debug') ? $e->getFile() : null,
                    'line'    => config('app.debug') ? $e->getLine() : null,
                ], 500);
            }
        });
    })->create();
