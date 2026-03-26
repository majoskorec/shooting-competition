<?php

declare(strict_types=1);

namespace App\Competition\Input\Model;

use App\Entity\Competitor;

final class InputCompetitor
{
    /**
     * @param array<int, InputTarget> $inputTargets
     */
    public function __construct(
        public readonly Competitor $competitor,
        public readonly array $inputTargets,
    ) {
    }

    public function getTotalScore(): int
    {
        return array_sum(array_map(
            fn (InputTarget $inputTarget) => $inputTarget->targetResult->getSubtotal(),
            $this->inputTargets,
        ));
    }
}
