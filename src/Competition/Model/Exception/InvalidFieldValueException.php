<?php

declare(strict_types=1);

namespace App\Competition\Model\Exception;

use InvalidArgumentException;

final class InvalidFieldValueException extends InvalidArgumentException
{
    public static function create(object $entity, string $fieldName): self
    {
        return new self(sprintf('Field "%s"."%s" has invalid value.', $entity::class, $fieldName));
    }
}
