<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use App\Competition\Draw\Exception\MissingStartNumberException;
use RuntimeException;

final readonly class CompetitorResult
{
    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     */
    public function __construct(
        public string $name,
        public int $finalResult,
        public array $competitorSubResults,
        public ?JuryEntryDto $juryEntryDto,
    ) {
    }

    public static function addCompetitorSubResults(
        self $competitorResult,
        CompetitorSubResults $competitorSubResults,
    ): self {
        $subResults = [...$competitorResult->competitorSubResults, $competitorSubResults];
        usort(
            $subResults,
            static fn (CompetitorSubResults $left, CompetitorSubResults $right): int => $right->compare($left),
        );

        return new self(
            name: $competitorResult->name,
            finalResult: $competitorResult->finalResult + $competitorSubResults->total,
            competitorSubResults: $subResults,
            juryEntryDto: null,
        );
    }

    public function getStartNumber(): int
    {
        $first = array_first($this->competitorSubResults)
            ?? throw new RuntimeException('Competitor result has no sub results');
        assert($first instanceof CompetitorSubResults);

        return $first->competitor->getStartNumber() ?? throw new MissingStartNumberException();
    }

    public function compare(self $other): int
    {
        if ($this->finalResult !== $other->finalResult) {
            return $this->finalResult <=> $other->finalResult;
        }

        $juryEntryComparison = JuryEntryDto::compare($this->juryEntryDto, $other->juryEntryDto);
        if ($juryEntryComparison !== 0) {
            return $juryEntryComparison;
        }

        $thisCompetitorSubResultsComparator = CompetitorSubResultsComparator::create($this->competitorSubResults);
        $otherCompetitorSubResultsComparator = CompetitorSubResultsComparator::create($other->competitorSubResults);

        return $thisCompetitorSubResultsComparator->compare($otherCompetitorSubResultsComparator);
    }
}
