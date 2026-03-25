<?php

namespace App;

use App\Serializer\JsonDoctrineTypeSerializerProvider;
use LogicException;
use Override;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Override]
    public function boot(): void
    {
        parent::boot();

        $container = $this->container;
        if ($container === null) {
            throw new LogicException('Container is not available during boot.');
        }

        JsonDoctrineTypeSerializerProvider::setup($container);
    }
}
