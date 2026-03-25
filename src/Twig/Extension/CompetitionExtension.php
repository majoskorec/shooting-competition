<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

final class CompetitionExtension
{
    #[AsTwigFunction('roundNumber')]
    public function roundNumber(int $startNumber, int $shootersInRound): int
    {
        return (int) ceil($startNumber / $shootersInRound);
    }

    #[AsTwigFunction('lastInRound')]
    public function lastInRound(int $startNumber, int $shootersInRound): bool
    {
        return $startNumber % $shootersInRound === 0;
    }

    /**
     * @param array<int> $array
     */
    #[AsTwigFilter('arraySum')]
    public function arraySum(array $array): int
    {
        return array_sum($array);
    }
}
