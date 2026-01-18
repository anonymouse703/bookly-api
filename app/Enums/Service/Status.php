<?php

namespace App\Enums\Service;

enum Status: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
    case Inactive = 'inactive';
}
