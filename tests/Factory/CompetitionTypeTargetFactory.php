<?php

namespace App\Tests\Factory;

use App\Entity\CompetitionTypeTarget;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<CompetitionTypeTarget>
 */
final class CompetitionTypeTargetFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return CompetitionTypeTarget::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competitionType' => CompetitionTypeFactory::new(),
            'displayOrder' => self::faker()->randomNumber(),
            'shotCount' => self::faker()->randomNumber(),
            'targetDefinition' => TargetDefinitionFactory::new(),
            'tieBreakPriority' => self::faker()->randomNumber(),
        ];
    }
}
