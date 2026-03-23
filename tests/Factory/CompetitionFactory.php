<?php

namespace App\Tests\Factory;

use App\Entity\Competition;
use App\Model\Enum\CompetitionStatus;
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
            'name' => self::faker()->text(255),
            'status' => self::faker()->randomElement(CompetitionStatus::cases()),
            'targetConfigurationSnapshot' => [],
        ];
    }
}
