<?php

declare(strict_types=1);

namespace App\Competition\Target;

use App\Competition\Target\Model\TargetSnapshot;
use App\Entity\CompetitionType;

final class TargetSnapshotFactory
{
    /**
     * @return array<TargetSnapshot>
     */
    public function createFromCompetitionType(CompetitionType $competitionType): array
    {
        $snapshots = [];

        foreach ($competitionType->getTargets() as $target) {
            $snapshots[] = TargetSnapshot::createFromCompetitionTypeTarget($target);
        }

        return $snapshots;
    }
}
