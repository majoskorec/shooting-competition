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
                $competition->getName()
            ));
        }

        $rounds = Rounds::createEmpty(count($competitors), $competition->getShootersInRound());

        $weaponGroups = [];
        $competitorsWithoutSharedWeapon = [];
        foreach ($competitors as $competitor) {
            $sharedWeaponCode = $competitor->getSharedWeaponCode();
            if ($sharedWeaponCode === null) {
                $competitorsWithoutSharedWeapon[] = $competitor;

                continue;
            }

            $weaponGroups[$sharedWeaponCode][] = $competitor;
        }

        uasort($weaponGroups, static fn (array $left, array $right): int => count($right) <=> count($left));

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

        $competitorsWithoutSharedWeapon = $this->shuffleCompetitors($competitorsWithoutSharedWeapon);
        foreach ($competitorsWithoutSharedWeapon as $competitor) {
            $rounds->addCompetitor($competitor, $this->randomizer);
        }

        $rounds->setStartNumbers($this->randomizer);
    }

    /**
     * @param array<int, Competitor> $competitors
     * @return array<int, Competitor>
     */
    private function shuffleCompetitors(array $competitors): array
    {
        if (count($competitors) < 2) {
            return $competitors;
        }

        return $this->randomizer->shuffleArray($competitors);
    }
}
