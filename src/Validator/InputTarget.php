<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Override;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class InputTarget extends Constraint
{
    public string $invalidValue = 'Táto hodnota nieje platná.';

    #[Override]
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
