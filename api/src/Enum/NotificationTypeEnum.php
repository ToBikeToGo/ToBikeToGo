<?php

declare(strict_types=1);

namespace App\Enum;

class NotificationTypeEnum
{
    public const EMPLOYEE_ADDED = 'employee_added';
    public const RESERVATION_CREATED = 'reservation_created';
    public const RESERVATION_MODIFIED = 'reservation_modified';
    public const RESERVATION_CANCELLED = 'reservation_cancelled';
    public const ACCOUNT_CREATION_REQUEST = 'account_creation_request';
    public const LEAVE_REQUEST = 'leave_request';
    public const LEAVE_CANCELLED = 'leave_cancelled';
    public const LEAVE_ACCEPTED = 'leave_accepted';
    public const LEAVE_MODIFIED = 'leave_modified';
    public const RESERVATION_REMINDER = 'reservation_reminder';
}
