<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use App\Entity\JuryEntry;

final class JuryEntryDto
{
    private function __construct(
        public int $id,
        public int $points,
    ) {
    }

    public static function create(JuryEntry $entry): self
    {
        return new self(
            id: $entry->getId(),
            points: $entry->getPoints(),
        );
    }

    public static function compare(?self $left, ?self $right): int
    {
        $leftPoints = $left?->points ?? 0;
        $rightPoints = $right?->points ?? 0;

        return $leftPoints <=> $rightPoints;
    }
}
