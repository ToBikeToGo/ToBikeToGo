<?php

namespace App\Constants;

final class Groups {
    const ALL_READ = [
        self::USER_READ,
        self::BIKE_READ,
        self::QUESTION_READ,
        self::PROPOSITION_READ,
        self::TYPE_QUESTION_READ,
        self::CATEGORY_READ,
        self::SHOP_READ,
        self::FRANCHISE_READ,
        self::MEDIA_READ,
        self::SCHEDULE_READ,
        self::COMMENT_READ,
        self::BOOKING_READ,
        self::REQUEST_READ,
    ];
    const USER_READ = 'user:read';
    const USER_WRITE = 'user:write';
    const USER_FRANCHISE_WRITE = 'user:franchise:write';
    const BIKE_READ = 'bike:read';
    const BIKE_WRITE = 'bike:write';
    const QUESTION_READ = 'question:read';
    const QUESTION_WRITE = 'question:write';
    const PROPOSITION_READ = 'proposition:read';
    const PROPOSITION_WRITE = 'proposition:write';
    const TYPE_QUESTION_READ = 'type_question:read';
    const TYPE_QUESTION_WRITE = 'type_question:write';
    const CATEGORY_READ = 'category:read';
    const CATEGORY_WRITE = 'category:write';
    const SHOP_READ = 'shop:read';
    const SHOP_WRITE = 'shop:write';
    const FRANCHISE_READ = 'franchise:read';
    const FRANCHISE_WRITE = 'franchise:write';
    const MEDIA_READ = 'media:read';
    const MEDIA_WRITE = 'media:write';
    const SCHEDULE_READ = 'schedule:read';
    const SCHEDULE_WRITE = 'schedule:write';
    const COMMENT_READ = 'comment:read';
    const COMMENT_WRITE = 'comment:write';
    const BOOKING_READ = 'booking:read';
    const BOOKING_WRITE = 'booking:write';
    const REQUEST_READ = 'request:read';
    const REQUEST_WRITE = 'request:write';
    const VACATION_WRITE = 'request:write';
    const NOTIFICATION_READ = 'notification:write';

}
