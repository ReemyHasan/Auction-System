<?php

namespace App\Enums;

enum UserTypeEnum:int {
    case Active = 1;
    case Inactive = 0;
    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
