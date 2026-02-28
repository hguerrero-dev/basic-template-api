<?php

namespace App\Modules\Core\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'mensaje' => $message,
            'datos' => $data
        ], $code);
    }

    protected function errorResponse(string $message, string $errorCode = 'INTERNAL_ERROR', int $code = 400, $details = null): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $errorCode,
            'mensaje' => $message,
        ];

        if ($details) {
            $response['details'] = $details;
        }

        return response()->json($response, $code);
    }
}
