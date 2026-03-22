<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\CompetitionTypeTarget;

final class TargetSnapshot
{
    public function __construct(
        public string $name,
        public TargetPointsSchema $pointsSchema,
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
