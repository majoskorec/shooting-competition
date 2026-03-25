<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\CompetitionTypeTarget;

final class TargetSnapshot
{
    public string $name;
    public array $pointsSchema;
    public int $displayOrder;
    public int $shotCount;
    public ?int $tieBreakPriority;

    public static function createFromCompetitionTypeTarget(CompetitionTypeTarget $competitionTypeTarget): self
    {
        $self = new self();
        $self->name = $competitionTypeTarget->getTargetDefinition()->getName();
        $self->pointsSchema = $competitionTypeTarget->getTargetDefinition()->getPointsSchema();
        $self->displayOrder = $competitionTypeTarget->getDisplayOrder();
        $self->shotCount = $competitionTypeTarget->getShotCount();
        $self->tieBreakPriority = $competitionTypeTarget->getTieBreakPriority();

        return $self;
    }
}
