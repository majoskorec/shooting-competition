<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('tests')
    ->exclude('var')
    ->exclude('translations')
    ->exclude('node_modules')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ]);

return new Config()
    ->setRiskyAllowed(true)
    ->setRules([
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder);
