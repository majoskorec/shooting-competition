<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Override;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class Presentation extends Constraint
{
    public string $shooterAlreadyInCompetitionMessage = 'Strelec {{ shooter }} je uz zaregistovaný na {{ competition }}.';
    public string $shooterAlreadyExistsMessage = 'Strelec {{ firstName }} {{ lastName }} už existuje.';
    public string $teamAlreadyExistsMessage = 'Družstvo {{ teamName }} už existuje na {{ competition }}.';
    public string $missingValuesMessage = 'Táto hodnota musí byť vyplnená.';

    #[Override]
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
