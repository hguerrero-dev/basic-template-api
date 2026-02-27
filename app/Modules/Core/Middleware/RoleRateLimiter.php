<?php

namespace App\Modules\Core\Middleware;

use App\Modules\Roles\Enums\SystemRole;
use Illuminate\Routing\Middleware\ThrottleRequests;

class RoleRateLimiter extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        // Use user ID if authenticated, otherwise use IP address
        return $request->user() ? $request->user()->id : $request->ip();
    }

    protected function getMaxAttempts($request)
    {
        if ($request->user()) {
            $user = $request->user();
            // if user has admin or super admin role, allow more attempts
            if ($user->hasRole(SystemRole::Admin->value) || $user->hasRole(SystemRole::SuperAdmin->value)) {
                return 1000;
            }
        }
        return 100;
    }
}
