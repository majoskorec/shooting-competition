<?php

declare(strict_types=1);

namespace App\Doctrine\Mapping;

use App\Serializer\JsonDoctrineTypeSerializerProvider;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;
use Override;
use Throwable;

abstract class JsonObjectType extends JsonType
{
    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        try {
            return JsonDoctrineTypeSerializerProvider::provide()->serialize($value, 'json');
        } catch (Throwable $e) {
            throw SerializationFailed::new($value, 'json', $e->getMessage(), $e);
        }
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        if (!is_string($value)) {
            throw InvalidType::new(
                $value,
                $this->getSerializationType(),
                [
                    'null',
                    'string',
                    'resource',
                ],
            );
        }

        if (trim($value) === '') {
            return null;
        }

        try {
            return JsonDoctrineTypeSerializerProvider::provide()->deserialize(
                $value,
                $this->getSerializationType(),
                'json',
            );
        } catch (Throwable $e) {
            throw SerializationFailed::new(
                $value,
                $this->getSerializationType(),
                $e->getMessage(),
                $e,
            );
        }
    }

    abstract protected function getSerializationType(): string;
}
