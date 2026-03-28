<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\TargetDefinition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Icons\IconRendererInterface;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Markup;

final class CompetitionExtension
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly IconRendererInterface $iconRenderer,
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

    #[AsTwigFilter('rankIcon', isSafe: ['html'])]
    public function rankIcon(int $rank): string
    {
        return match ($rank) {
            1 => sprintf('1. %s', $this->iconRenderer->renderIcon('fa6-solid:medal', ['style' => "color: #d4af37;"])),
            2 => sprintf('2. %s', $this->iconRenderer->renderIcon('fa6-solid:medal', ['style' => "color: #9aa4b2;"])),
            3 => sprintf('3. %s', $this->iconRenderer->renderIcon('fa6-solid:medal', ['style' => "color: #b87333;"])),
            default => (string) $rank,
        };
    }

    #[AsTwigFilter('checkCircle', isSafe: ['html'])]
    public function checkCircle(bool $check, string $class = 'text-success', string $style = 'height: 1em;'): string
    {
        return $check
            ? $this->iconRenderer->renderIcon('bi:check-circle-fill', ['class' => $class, 'style' => $style])
            : '';
    }
}
