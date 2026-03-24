<?php

declare(strict_types=1);

namespace App\Story;

use App\Entity\Competitor;
use App\Model\Enum\CompetitionStatus;
use App\Model\Enum\CompetitorStatus;
use App\Tests\Factory\CompetitionFactory;
use App\Tests\Factory\CompetitionTeamFactory;
use App\Tests\Factory\CompetitionTypeFactory;
use App\Tests\Factory\CompetitorFactory;
use App\Tests\Factory\ShooterFactory;
use Override;
use Random\Randomizer;
use Symfony\Component\Clock\DatePoint;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\faker;
use function Zenstruck\Foundry\Persistence\save;

#[AsFixture(name: 'random-competition-presentation')]
final class RandomCompetitionPresentationStory extends Story
{
    public function __construct(
        private readonly Randomizer $randomizer,
    ) {
    }

    #[Override]
    public function build(): void
    {
        $competitionType = CompetitionTypeFactory::findOrCreate([]);
        $competition = CompetitionFactory::createOne([
            'competitionStart' => new DatePoint('now'),
            'competitionType' => $competitionType,
            'status' => CompetitionStatus::Presentation,
        ]);

        $maxTeamMemberCount = $competition->getTeamMemberCount();
        $shooters = ShooterFactory::all();
        $shooters = $this->randomizer->shuffleArray($shooters);
        $competitors = [];
        $competitionTeam = null;
        $teamMemberCount = 0;
        foreach ($shooters as $shooter) {
            if ($competitionTeam === null) {
                $competitionTeam = CompetitionTeamFactory::createOne([
                    'competition' => $competition,
                ]);
            }
            $competitors[] = CompetitorFactory::createOne([
                'competition' => $competition,
                'shooter' => $shooter,
                'status' => CompetitorStatus::Registered,
                'competitionTeam' => $maxTeamMemberCount === 0 ? null : $competitionTeam,
            ]);
            $teamMemberCount++;
            if ($teamMemberCount === $maxTeamMemberCount) {
                $competitionTeam = null;
                $teamMemberCount = 0;
            }
        }

        /** @var array<Competitor> $competitors */
        $competitors = $this->randomizer->shuffleArray($competitors);
        $sharedWeaponCode = 'a';
        $sharedWeaponCount = faker()->numberBetween(2, 5);
        foreach ($competitors as $competitor) {
            $competitor->setSharedWeaponCode($sharedWeaponCode);
            save($competitor);
            $sharedWeaponCount--;
            if ($sharedWeaponCount === 0) {
                $sharedWeaponCount = faker()->numberBetween(2, 5);
                $sharedWeaponCode++;
            }

            if ($sharedWeaponCode === 'f') {
                return;
            }
        }
    }
}
