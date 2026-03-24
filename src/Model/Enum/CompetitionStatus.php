<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CompetitionStatus: string
{
    case Draft = 'draft';
    case Presentation = 'presentation';
    case InProgress = 'in_progress';
    case ReadyForClosure = 'ready_for_closure';
    case Finalized = 'finalized';
    case Closed = 'closed';
}
