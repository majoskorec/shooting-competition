<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\CompetitionTypeTarget;

final class TargetSnapshot
{
    /**
     * @param array<int, int> $pointsSchema
     */
    public function __construct(
        public string $name,
        public array $pointsSchema,
        public int $displayOrder,
        public int $shotCount,
        public ?int $tieBreakPriority,
    ) {
    }

    public static function createFromCompetitionTypeTarget(CompetitionTypeTarget $competitionTypeTarget): self
    {
        return new self(
            name: $competitionTypeTarget->getTargetDefinition()->getName(),
            pointsSchema: $competitionTypeTarget->getTargetDefinition()->getPointsSchema(),
            displayOrder: $competitionTypeTarget->getDisplayOrder(),
            shotCount: $competitionTypeTarget->getShotCount(),
            tieBreakPriority: $competitionTypeTarget->getTieBreakPriority(),
        );
    }
}
