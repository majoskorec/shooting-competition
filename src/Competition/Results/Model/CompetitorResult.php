<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

final readonly class CompetitorResult
{
    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     */
    public function __construct(
        public string $name,
        public int $finalResult,
        public array $competitorSubResults,
    ) {
    }

    public static function addCompetitorSubResults(
        self $competitorResult,
        CompetitorSubResults $competitorSubResults,
    ): self {
        $subResults = [...$competitorResult->competitorSubResults, $competitorSubResults];
        usort(
            $subResults,
            static fn (CompetitorSubResults $left, CompetitorSubResults $right): int => $left->compare($right),
        );

        return new self(
            name: $competitorResult->name,
            finalResult: $competitorResult->finalResult + $competitorSubResults->total,
            competitorSubResults: $subResults,
        );
    }

    public function compare(self $other): int
    {
        if ($this->finalResult !== $other->finalResult) {
            return $this->finalResult <=> $other->finalResult;
        }

        $thisCompetitorSubResultsComparator = CompetitorSubResultsComparator::create($this->competitorSubResults);
        $otherCompetitorSubResultsComparator = CompetitorSubResultsComparator::create($other->competitorSubResults);

        return $thisCompetitorSubResultsComparator->compare($otherCompetitorSubResultsComparator);
    }
}
