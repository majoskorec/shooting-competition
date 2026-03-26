<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

final class CompetitorSubResultsComparator
{
    /**
     * @param array<string, SubResult> $subResults
     */
    private function __construct(
        private readonly array $subResults,
    ) {
    }

    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     */
    public static function create(array $competitorSubResults): self
    {
        $result = [];
        foreach ($competitorSubResults as $competitorSubResult) {
            foreach ($competitorSubResult->subResults as $subResult) {
                $result[$subResult->name] = SubResult::add($subResult, $result[$subResult->name] ?? null);
            }
        }

        uasort(
            $result,
            static fn (SubResult $left, SubResult $right): int => $right->tieBreakPriority <=> $left->tieBreakPriority,
        );

        return new self($result);
    }

    public function compare(self $other): int
    {
        foreach ($this->subResults as $key => $subResult) {
            if ($subResult->result === $other->subResults[$key]->result) {
                continue;
            }

            return $subResult->result <=> $other->subResults[$key]->result;
        }

        return 0;
    }
}
