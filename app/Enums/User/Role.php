<?php

namespace App\Enums\User;

use App\Enums\Traits\EnumToArray;

enum Role: string
{
    use EnumToArray;

    case Admin = 'admin';
    case Provider = 'provider';
    case Customer = 'customer';
}
