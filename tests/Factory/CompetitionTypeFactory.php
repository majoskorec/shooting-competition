<?php

namespace App\Tests\Factory;

use App\Entity\CompetitionType;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<CompetitionType>
 */
final class CompetitionTypeFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return CompetitionType::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->text(255),
        ];
    }
}
