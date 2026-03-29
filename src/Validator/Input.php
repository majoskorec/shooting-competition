<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Override;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class Input extends Constraint
{
    public string $lessShots = 'Strelec <b>{{ shooter }}</b> na terči <b>{{ targetName }}</b> nastrielal menej rán ako by mal mať.';
    public string $moreShots = 'Strelec <b>{{ shooter }}</b> na terči <b>{{ targetName }}</b> nastrielal viac rán ako by mal mať.';

    #[Override]
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
