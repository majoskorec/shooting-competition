<?php

declare(strict_types=1);

namespace App\Story;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Model\CompetitorStatus;
use App\Competition\Target\TargetSnapshotFactory;
use App\Entity\CompetitionCategoryRule;
use App\Entity\Competitor;
use App\Tests\Factory\CompetitionCategoryFactory;
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
        private readonly TargetSnapshotFactory $targetSnapshotFactory,
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
            'targetConfigurationSnapshot' => $this->targetSnapshotFactory->createFromCompetitionType($competitionType),
        ]);

        $categoryWomen = CompetitionCategoryFactory::createOne([
            'competition' => $competition,
            'name' => 'Ženy',
            'rule' => CompetitionCategoryRule::Women,
        ]);
        $categorySeniors = CompetitionCategoryFactory::createOne([
            'competition' => $competition,
            'name' => 'Seniori',
            'rule' => CompetitionCategoryRule::MenSeniors,
        ]);
        $categoryVeterans = CompetitionCategoryFactory::createOne([
            'competition' => $competition,
            'name' => 'Veteráni',
            'rule' => CompetitionCategoryRule::MenVeterans,
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

            $categories = [
                faker()->numberBetween(1, 100) > 66 ? $categoryWomen : null,
                faker()->numberBetween(1, 100) > 66 ? $categorySeniors : null,
                faker()->numberBetween(1, 100) > 66 ? $categoryVeterans : null,
            ];
            $categories = array_filter($categories);

            $competitors[] = CompetitorFactory::createOne([
                'categories' => $categories,
                'competition' => $competition,
                'competitionTeam' => $maxTeamMemberCount === 0 ? null : $competitionTeam,
                'shooter' => $shooter,
                'status' => CompetitorStatus::Registered,
            ]);
            $teamMemberCount++;
            if ($teamMemberCount !== $maxTeamMemberCount) {
                continue;
            }

            $competitionTeam = null;
            $teamMemberCount = 0;
        }

        /** @var array<Competitor> $competitors */
        $competitors = $this->randomizer->shuffleArray($competitors);
        $sharedWeaponCode = 1;
        $sharedWeaponCount = faker()->numberBetween(2, 5);
        foreach ($competitors as $competitor) {
            $competitor->setSharedWeaponCode((string) $sharedWeaponCode);
            save($competitor);
            $sharedWeaponCount--;
            if ($sharedWeaponCount === 0) {
                $sharedWeaponCount = faker()->numberBetween(2, 5);
                $sharedWeaponCode++;
            }

            if ($sharedWeaponCode >= 6) {
                return;
            }
        }
    }
}
