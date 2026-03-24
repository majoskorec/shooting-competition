<?php

declare(strict_types=1);

namespace App\Twig\Extension;

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
}
