<?php

namespace App\Tests\Factory;

use App\Entity\Shooter;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Shooter>
 */
final class ShooterFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return Shooter::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'firstName' => self::faker()->unique()->firstName(),
            'lastName' => self::faker()->unique()->lastName(),
            'club' => self::faker()->city(),
            'email' => self::faker()->unique()->email(),
        ];
    }
}
