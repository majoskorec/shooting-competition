<?php

declare(strict_types=1);

namespace App\Model\Factory;

use App\Entity\CompetitionType;
use App\Model\TargetSnapshot;

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
