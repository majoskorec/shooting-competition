<?php

namespace App\Tests\Factory;

use App\Entity\TargetDefinition;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<TargetDefinition>
 */
final class TargetDefinitionFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return TargetDefinition::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->text(255),
            'shortName' => self::faker()->text(32),
            'pointsSchema' => [],
        ];
    }
}
