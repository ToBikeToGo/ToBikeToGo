<?php

declare(strict_types=1);

namespace App\Enum\Groups;

enum UserGroupsEnum: string
{
    case READ = 'user:read';
    case WRITE = 'user:write';

    case ADMIN_READ = 'user:read:admin';
    case ADMIN_WRITE = 'user:write:admin';
}
