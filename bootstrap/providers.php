<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Users\Providers\UserServiceProvider::class,
    App\Modules\Auth\Providers\AuthServiceProvider::class,
    App\Modules\Roles\Providers\RoleServiceProvider::class,
];
