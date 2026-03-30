<?php

declare(strict_types=1);

namespace App\Form\Dto;

use App\Validator\InputTarget;

#[InputTarget]
final class InputTargetDto
{
    /**
     * @var array<int, int|string>
     */
    public array $points = [];

    /**
     * @return array<int, int>
     */
    public function toHitBreakdown(): array
    {
        return array_map(
            static fn (int|string $value): int => (int) $value,
            $this->points,
        );
    }
}
