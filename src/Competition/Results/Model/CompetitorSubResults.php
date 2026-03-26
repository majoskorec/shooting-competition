<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use App\Entity\Competitor;
use RuntimeException;

final readonly class CompetitorSubResults
{
    public int $total;

    /**
     * @param array<string, SubResult> $subResults
     */
    public function __construct(
        public Competitor $competitor,
        public array $subResults,
    ) {
        $this->total = $this->getTotal();
    }

    public function compare(self $other): int
    {
        if ($this->total !== $other->total) {
            return $this->total <=> $other->total;
        }

        $results = $this->subResults;
        uasort(
            $results,
            static fn (SubResult $left, SubResult $right): int => $right->tieBreakPriority <=> $left->tieBreakPriority,
        );

        foreach ($results as $result) {
            $otherResult = $other->getSubResult($result->name);
            if ($result->result === $otherResult->result) {
                continue;
            }

            return $result->result <=> $otherResult->result;
        }

        return 0;
    }

    public function getSubResult(string $name): SubResult
    {
        return $this->subResults[$name]
            ?? throw new RuntimeException(sprintf('Sub result with name %s not found', $name));
    }

    private function getTotal(): int
    {
        $result = 0;
        foreach ($this->subResults as $subResult) {
            $result += $subResult->result;
        }

        return $result;
    }
}
