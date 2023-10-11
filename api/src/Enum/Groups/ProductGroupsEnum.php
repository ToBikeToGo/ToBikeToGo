<?php

declare(strict_types=1);

namespace App\Enum\Groups;

enum ProductGroupsEnum: string
{
    case READ_AS_LOGGED_USER = 'product:read:logged';
    case READ_AS_AUTHORIZED_USER = 'product:read:authorized';
}
