<?php

declare(strict_types=1);

use App\Kernel;

date_default_timezone_set('UTC');

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

// phpcs:ignore SlevomatCodingStandard.Functions.StaticClosure.ClosureNotStatic
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']); // @phpstan-ignore argument.type
};
