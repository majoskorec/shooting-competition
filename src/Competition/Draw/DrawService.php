<?php

declare(strict_types=1);

namespace App\Competition\Draw;

use App\Competition\Draw\Exception\StartNumberAssignmentException;
use App\Competition\Draw\Model\Rounds;
use App\Competition\Model\CompetitionStatus;
use App\Entity\Competition;
use App\Entity\Competitor;
use Random\Randomizer;

final class DrawService
{
    public function __construct(
        private readonly Randomizer $randomizer,
    ) {
    }

    public function __invoke(Competition $competition): void
    {
        $this->validateCompetition($competition);
        $competitors = $competition->getCompetitors()->toArray();
        $rounds = Rounds::createEmpty(count($competitors), $competition->getShootersInRound());

        [$withoutSharedWeaponGroup, $weaponGroups] = $this->categorizeCompetitorsBySharedWeapon($competitors);
        uasort($weaponGroups, static fn (array $left, array $right): int => count($right) <=> count($left));

        $this->addCompetitorsWithSharedWeaponsToRounds($weaponGroups, $rounds);
        $this->addCompetitorsWithoutSharedWeaponsToRounds($withoutSharedWeaponGroup, $rounds);

        $rounds->setStartNumbers($this->randomizer);
    }

    /**
     * @param array<Competitor> $withoutSharedWeaponGroup
     */
    private function addCompetitorsWithoutSharedWeaponsToRounds(array $withoutSharedWeaponGroup, Rounds $rounds): void
    {
        $withoutSharedWeaponGroup = $this->shuffleCompetitors($withoutSharedWeaponGroup);
        foreach ($withoutSharedWeaponGroup as $competitor) {
            $rounds->addCompetitor($competitor, $this->randomizer);
        }
    }

    /**
     * @param array<string, array<Competitor>> $weaponGroups
     */
    private function addCompetitorsWithSharedWeaponsToRounds(array $weaponGroups, Rounds $rounds): void
    {
        foreach ($weaponGroups as $sharedWeaponCode => $groupedCompetitors) {
            if (count($groupedCompetitors) > $rounds->shootersInRound) {
                throw new StartNumberAssignmentException(sprintf(
                    'Rozlosovanie nie je možné. Zbraň "%s" používa %d súťažiacich, ale dostupných je len %d rúd.',
                    $sharedWeaponCode,
                    count($groupedCompetitors),
                    $rounds->roundCount(),
                ));
            }

            foreach ($groupedCompetitors as $competitor) {
                $rounds->addCompetitor($competitor, $this->randomizer);
            }
        }
    }

    /**
     * @param array<Competitor> $competitors
     * @return array{0: array<Competitor>, 1: array<string, array<Competitor>>}
     */
    private function categorizeCompetitorsBySharedWeapon(array $competitors): array
    {
        $weaponGroups = [];
        $withoutSharedWeaponGroup = [];
        foreach ($competitors as $competitor) {
            $sharedWeaponCode = $competitor->getSharedWeaponCode();
            if ($sharedWeaponCode === null) {
                $withoutSharedWeaponGroup[] = $competitor;

                continue;
            }

            $weaponGroups[$sharedWeaponCode][] = $competitor;
        }

        return [$withoutSharedWeaponGroup, $weaponGroups];
    }

    /**
     * @param array<Competitor> $competitors
     * @return array<Competitor>
     */
    private function shuffleCompetitors(array $competitors): array
    {
        if (count($competitors) < 2) {
            return $competitors;
        }
        /** @var array<Competitor> $result */
        $result = $this->randomizer->shuffleArray($competitors);

        return $result;
    }

    private function validateCompetition(Competition $competition): void
    {
        if ($competition->getStatus() !== CompetitionStatus::Presentation) {
            throw new StartNumberAssignmentException(sprintf(
                'Rozlosovanie nie je možné. Očakávaný status súťaže je "%s", ale aktuálny status je "%s".',
                CompetitionStatus::Presentation->value,
                $competition->getStatus()->value,
            ));
        }

        $competitors = $competition->getCompetitors()->toArray();
        if (count($competitors) === 0) {
            throw new StartNumberAssignmentException(sprintf(
                'Rozlosovanie nie je možné. Souťaž "%s" nemá žiadneho súťažiaceho.',
                $competition->getName(),
            ));
        }
    }
}
