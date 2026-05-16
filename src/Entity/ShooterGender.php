<?php

declare(strict_types=1);

namespace App\Entity;

enum ShooterGender: string
{
    case Male = 'muz';
    case Female = 'zena';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Muž',
            self::Female => 'Žena',
        };
    }
}
