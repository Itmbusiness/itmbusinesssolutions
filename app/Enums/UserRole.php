<?php

namespace App\Enums;

enum UserRole: string
{
    case Client = 'client';
    case Consultant = 'consultant';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Client => 'Client',
            self::Consultant => 'Consultant or agent',
            self::Admin => 'Administrator',
        };
    }

    /**
     * Roles a visitor may pick when registering. Administrator is granted
     * deliberately, never self-selected at sign-up.
     *
     * @return array<int, self>
     */
    public static function selfSelectable(): array
    {
        return [self::Client, self::Consultant];
    }
}
