<?php

declare(strict_types=1);

namespace App\Serializer;

use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonDoctrineTypeSerializerProvider
{
    public const string SERVICE_ID = 'app.serializer.json_doctrine_type_serializer_provider';

    private static ?ContainerInterface $container = null;

    public static function setup(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function provide(): SerializerInterface
    {
        if (self::$container === null) {
            throw new RuntimeException('The provider was not set up.');
        }

        $serializer = self::$container->get(self::SERVICE_ID);
        if (!$serializer instanceof SerializerInterface) {
            throw new RuntimeException('The retrieved service is not a SerializerInterface instance.');
        }

        return $serializer;
    }
}
