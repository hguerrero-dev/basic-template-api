<?php

namespace App\Modules\Core;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            // ...otros middlewares...
            \App\Modules\Core\Middleware\SecureHeaders::class,
        ],
    ];
}
