<?php

namespace App\Tests\Factory;

use App\Competition\Model\CompetitionStatus;
use App\Entity\Competition;
use DateTimeImmutable;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Competition>
 */
final class CompetitionFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Competition::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competitionStart' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'competitionType' => CompetitionTypeFactory::new(),
            'name' => self::faker()->words(3, true),
            'status' => self::faker()->randomElement(CompetitionStatus::cases()),
            'targetConfigurationSnapshot' => [],
            'teamMemberCount' => self::faker()->randomElement([0, 3, 4]),
            'shootersInRound' => self::faker()->randomElement([6, 7, 8, 9, 10, 11, 12]),
            'location' => self::faker()->address,
        ];
    }
}
