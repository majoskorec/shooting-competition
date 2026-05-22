<?php

declare(strict_types=1);

namespace App\Entity;

enum CompetitionCategoryRule: string
{
    case Juniors = 'juniors';
    case MenSeniors = 'men_seniors';
    case MenVeterans = 'men_veterans';
    case Women = 'women';

    public function label(): string
    {
        return match ($this) {
            self::Juniors => 'Juniori',
            self::MenSeniors => 'Muži seniori',
            self::MenVeterans => 'Muži veteráni',
            self::Women => 'Ženy',
        };
    }
}
