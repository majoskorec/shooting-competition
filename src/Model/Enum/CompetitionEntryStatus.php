<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CompetitionEntryStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Withdrawn = 'withdrawn';
    case Disqualified = 'disqualified';
}
