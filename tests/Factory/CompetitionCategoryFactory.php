<?php

namespace App\Tests\Factory;

use App\Entity\CompetitionCategory;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<CompetitionCategory>
 */
final class CompetitionCategoryFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return CompetitionCategory::class;
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
