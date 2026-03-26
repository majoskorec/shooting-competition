<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use App\Entity\Competition;

final class Results
{
    /**
     * @param array<CompetitorResultWithRank> $competitorsResultsWithRank
     */
    public function __construct(
        public readonly Competition $competition,
        public readonly array $competitorsResultsWithRank,
        public readonly Category $category,
    ) {
    }
}
