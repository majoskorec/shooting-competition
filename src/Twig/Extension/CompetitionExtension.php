<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\TargetDefinition;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

final class CompetitionExtension
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

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

    #[AsTwigFunction('targetShortName')]
    public function targetShortName(string $targetName): string
    {
        $target = $this->entityManager->getRepository(TargetDefinition::class)->findOneBy(['name' => $targetName]);

        return $target?->getShortName() ?? '';
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
