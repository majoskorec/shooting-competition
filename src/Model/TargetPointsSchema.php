<?php

declare(strict_types=1);

namespace App\Model;

final readonly class TargetPointsSchema
{
    /**
     * @param list<int> $points
     */
    public function __construct(
        public array $points,
    ) {
    }
}
