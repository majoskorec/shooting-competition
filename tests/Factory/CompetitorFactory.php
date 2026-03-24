<?php

namespace App\Tests\Factory;

use App\Entity\Competitor;
use App\Model\Enum\CompetitorStatus;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Competitor>
 */
final class CompetitorFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Competitor::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'competition' => CompetitionFactory::new(),
            'shooter' => ShooterFactory::new(),
            'status' => self::faker()->randomElement(CompetitorStatus::cases()),
        ];
    }
}
