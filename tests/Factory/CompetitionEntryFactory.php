<?php

namespace App\Tests\Factory;

use App\Entity\CompetitionEntry;
use App\Model\Enum\CompetitionEntryStatus;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<CompetitionEntry>
 */
final class CompetitionEntryFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return CompetitionEntry::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competition' => CompetitionFactory::new(),
            'shooter' => ShooterFactory::new(),
            'status' => self::faker()->randomElement(CompetitionEntryStatus::cases()),
        ];
    }
}
