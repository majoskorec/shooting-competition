<?php

namespace App\Tests\Factory;

use App\Entity\CompetitionTeam;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<CompetitionTeam>
 */
final class CompetitionTeamFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return CompetitionTeam::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competition' => CompetitionFactory::new(),
            'name' => self::faker()->unique()->city(),
        ];
    }
}
