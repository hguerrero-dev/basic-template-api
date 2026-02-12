<?php

namespace App\Modules\Users\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';
    case Banned = 'banned';
}
