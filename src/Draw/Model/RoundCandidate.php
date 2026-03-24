<?php

declare(strict_types=1);

namespace App\Draw\Model;

use Random\Randomizer;

final class RoundCandidate
{
    /**
     * @param array<Round> $candidates
     */
    private function __construct(
        private array $candidates,
        private readonly bool $withSharedWeapon,
    ) {
    }

    public static function createEmpty(?string $sharedWeaponCode): self
    {
        return new self([], $sharedWeaponCode !== null);
    }

    public function tryAdd(Round $round): void
    {
        if ($this->withSharedWeapon) {
            $this->addIfLowerSize($round);

            return;
        }

        $this->addIfNotSet($round);
    }

    public function getOne(Randomizer $randomizer): ?Round
    {
        if (count($this->candidates) === 0) {
            return null;
        }

        if ($this->withSharedWeapon) {
            return array_first($randomizer->shuffleArray($this->candidates));
        }

        return array_first($this->candidates);
    }

    private function addIfNotSet(Round $round): void
    {
        if ($this->candidates !== []) {
            return;
        }

        $this->candidates[] = $round;
    }

    private function addIfLowerSize(Round $round): void
    {
        if ($this->candidates === []) {
            $this->candidates[] = $round;

            return;
        }

        $first = array_first($this->candidates);
        if ($first->fillCount() > $round->fillCount()) {
            $this->candidates = [$round];

            return;
        }

        if ($first->fillCount() < $round->fillCount()) {
            return;
        }

        $this->candidates[] = $round;
    }
}
