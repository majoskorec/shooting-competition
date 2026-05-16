<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Shooter;
use App\Entity\ShooterGender;
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
            'birthYear' => self::faker()->numberBetween(1940, 2015),
            'firstName' => self::faker()->unique()->firstName(),
            'gender' => self::faker()->randomElement(ShooterGender::cases()),
            'lastName' => self::faker()->unique()->lastName(),
            'club' => self::faker()->city(),
            'email' => self::faker()->unique()->email(),
        ];
    }
}
