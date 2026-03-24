<?php

declare(strict_types=1);

namespace App\Draw\Model;

use App\Draw\Exception\StartNumberAssignmentException;
use App\Entity\Competitor;
use Random\Randomizer;

final class Rounds
{
    /**
     * @param array<int, Round> $rounds
     */
    public function __construct(
        public readonly array $rounds,
        public readonly int $shootersInRound,
    ) {
    }

    public static function createEmpty(int $competitorsCount, int $shootersInRound): self
    {
        $roundCount = (int) ceil($competitorsCount / $shootersInRound);

        $rounds = [];
        for ($i = 0; $i < $roundCount; $i++) {
            $rounds[] = Round::createEmpty();
        }

        return new self($rounds, $shootersInRound);
    }

    public function roundCount(): int
    {
        return count($this->rounds);
    }

    public function addCompetitor(Competitor $competitor, Randomizer $randomizer): void
    {
        $round = $this->findFreeRound($competitor->getSharedWeaponCode(), $randomizer);
        if ($round === null) {
            throw new StartNumberAssignmentException(sprintf(
                'Rozlosovanie nie je možné. Nepodarilo sa umiestniť súťažiaceho "%s" so zdieľanou zbraňou "%s".',
                $competitor->getShooter()->getFullName(),
                $competitor->getSharedWeaponCode() ?? 'n/a',
            ));
        }

        $round->addCompetitor($competitor);
    }

    public function setStartNumbers(Randomizer $randomizer): void
    {
        $startNumber = 1;
        foreach ($this->rounds as $round) {
            $competitors = $randomizer->shuffleArray($round->getCompetitors());
            foreach ($competitors as $competitor) {
                $competitor->setStartNumber($startNumber++);
            }
        }
    }

    private function findFreeRound(?string $sharedWeaponCode, Randomizer $randomizer): ?Round
    {
        $roundCandidate = RoundCandidate::createEmpty($sharedWeaponCode);
        foreach ($this->rounds as $round) {
            $roundSize = $round->fillCount();
            if ($roundSize >= $this->shootersInRound) {
                continue;
            }

            if ($round->containsSharedWeaponCode($sharedWeaponCode)) {
                continue;
            }

            $roundCandidate->tryAdd($round);
        }

        return $roundCandidate->getOne($randomizer);
    }
}
