<?php

use App\Modules\Core\Constants\MiddlewareAlias;
use App\Modules\Core\Middleware\RoleRateLimiter;
use App\Modules\Core\Middleware\SecureHeaders;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/health'
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            MiddlewareAlias::ROLE_RATE_LIMITER => RoleRateLimiter::class,
        ]);

        // Middleware de seguridad para el grupo API
        $middleware->group('api', [
            SecureHeaders::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        // 1. Rate Limiting (429)
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'TOO_MANY_REQUESTS',
                    'message' => 'Has excedido el límite de peticiones. Intenta de nuevo en unos segundos.'
                ], 429);
            }
        });

        // 2. Autenticación (401)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'UNAUTHORIZED',
                    'message' => 'Authentication required. Please provide valid credentials.'
                ], 401);
            }
        });

        // 3. Permisos (403)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'FORBIDDEN',
                    'message' => 'You do not have permission to access this resource.'
                ], 403);
            }
        });
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'FORBIDDEN',
                    'message' => 'No tienes los permisos necesarios para realizar esta acción.'
                ], 403);
            }
        });

        // 4. Validación (422)
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'VALIDATION_ERROR',
                    'message' => 'Los datos enviados no son válidos.',
                    'details' => $e->errors(),
                ], 422);
            }
        });

        // 5. Recurso no encontrado (404)
        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'NOT_FOUND',
                    'message' => 'El recurso solicitado no existe o la ruta es incorrecta.'
                ], 404);
            }
        });

        // 6. Método no permitido (405)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'METHOD_NOT_ALLOWED',
                    'message' => 'El método HTTP utilizado no está permitido para esta ruta.'
                ], 405);
            }
        });

        // 7. Error de base de datos (500)
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'DATABASE_ERROR',
                    'message' => 'Error de base de datos.',
                    'details' => $e->getMessage(),
                ], 500);
            }
        });

        // 8. Error genérico (500)
        $exceptions->render(function (\Exception $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error'   => 'INTERNAL_SERVER_ERROR',
                    'message' => 'Ha ocurrido un error inesperado.',
                    'details' => $e->getMessage(),
                ], 500);
            }
        });
    })->create();
