<?php

declare(strict_types=1);

namespace App\Competition\Input\Model;

use App\Competition\Target\Model\TargetSnapshot;
use App\Entity\TargetResult;

final class InputTarget
{
    public TargetSnapshot $targetSnapshot;
    public TargetResult $targetResult;
    public int $competitorStartNumber;
    public int $targetIndex;
    public int $competitionId;

    public static function create(
        TargetSnapshot $targetSnapshot,
        TargetResult $targetResult,
        int $competitorStartNumber,
        int $targetIndex,
        int $competitionId
    ): self {
        $self = new self();
        $self->targetSnapshot = $targetSnapshot;
        $self->targetResult = $targetResult;
        $self->competitorStartNumber = $competitorStartNumber;
        $self->targetIndex = $targetIndex;
        $self->competitionId = $competitionId;

        return $self;
    }
}
