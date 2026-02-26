<?php

namespace App\Modules\Roles\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const CACHE_TAG = 'roles';
    public const CACHE_KEY_LIST = 'roles_list';
    public const CACHE_KEY_DETAIL = 'role_detail';
    public const CACHE_TAG_PERMISSIONS = 'permissions';
    public const CACHE_KEY_PERMISSIONS_GROUPED = 'permissions_grouped';
}
