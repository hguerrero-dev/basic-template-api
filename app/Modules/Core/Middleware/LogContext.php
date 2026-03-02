<?php

namespace App\Modules\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogContext
{
    public function handle(Request $request, Closure $next)
    {
        // => Generate a unique request ID for tracing.......$
        $requestId = (string) Str::uuid();

        // => Add context to the logger for this request
        Log::withContext([
            'request_id' => $requestId,
            'user_id'    => optional($request->user())->id,
            'ip'         => $request->ip(),
            'route'      => $request->path(),
            'method'     => $request->method(),
            'authorization' => $request->header('Authorization'),
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-Id', $requestId);

        return $response;
    }
}
