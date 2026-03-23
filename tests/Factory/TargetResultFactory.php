<?php

namespace App\Tests\Factory;

use App\Entity\TargetResult;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<TargetResult>
 */
final class TargetResultFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return TargetResult::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competitionEntry' => CompetitionEntryFactory::new(),
            'consistent' => self::faker()->boolean(),
            'hitBreakdown' => [],
            'targetName' => self::faker()->text(255),
        ];
    }
}
