<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\User;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return User::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'roles' => [],
            'fullName' => self::faker()->firstName() . ' ' . self::faker()->lastName(),
            'email' => self::faker()->unique()->email(),
            'password' => self::faker()->password(),
        ];
    }
}
