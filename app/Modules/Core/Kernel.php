<?php

namespace App\Modules\Core;

use App\Modules\Core\Middleware\SecureHeaders;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            SecureHeaders::class,
        ],
    ];
}
