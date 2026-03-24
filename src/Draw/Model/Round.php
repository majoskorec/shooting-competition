<?php

declare(strict_types=1);

namespace App\Draw\Model;

use App\Entity\Competitor;

final class Round
{
    /**
     * @param array<Competitor> $competitors
     */
    public function __construct(
        private array $competitors,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(competitors: []);
    }

    public function fillCount(): int
    {
        return count($this->competitors);
    }

    public function containsSharedWeaponCode(?string $sharedWeaponCode): bool
    {
        if ($sharedWeaponCode === null) {
            return false;
        }

        return array_any(
            $this->competitors,
            fn (Competitor $competitor): bool => $competitor->getSharedWeaponCode() === $sharedWeaponCode,
        );
    }

    public function addCompetitor(Competitor $competitor): void
    {
        $this->competitors[] = $competitor;
    }

    /**
     * @return array<Competitor>
     */
    public function getCompetitors(): array
    {
        return $this->competitors;
    }
}
