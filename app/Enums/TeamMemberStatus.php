<?php

namespace App\Enums;

enum TeamMemberStatus: string
{
    case Active = 'active';
    case Transferred = 'transferred';
    case Retired = 'retired';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Transferred => 'Transferred',
            self::Retired => 'Retired',
        };
    }
}
