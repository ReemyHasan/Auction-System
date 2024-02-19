<?php

namespace App\Enums;

enum UserTypeEnum:int {
    case Vendor = 1;
    case Customer = 2;
    case Both = 3;
    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
