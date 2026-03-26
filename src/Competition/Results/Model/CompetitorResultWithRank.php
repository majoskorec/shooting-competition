<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

final class CompetitorResultWithRank
{
    public function __construct(
        public CompetitorResult $competitorResult,
        public int $rank,
    ) {
    }
}
