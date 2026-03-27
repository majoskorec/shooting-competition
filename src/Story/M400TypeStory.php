<?php

declare(strict_types=1);

namespace App\Story;

use App\Tests\Factory\CompetitionTypeFactory;
use App\Tests\Factory\CompetitionTypeTargetFactory;
use App\Tests\Factory\TargetDefinitionFactory;
use Override;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\save;

#[AsFixture(name: 'm400Type')]
final class M400TypeStory extends Story
{
    #[Override]
    public function build(): void
    {
        $type = CompetitionTypeFactory::createOne([
            'name' => 'M400',
        ]);

        $foxTarget = TargetDefinitionFactory::createOne([
            'name' => 'Líška - Terč medzinárodný redukovaný na 50m',
            'shortName' => 'Líška',
            'pointsSchema' => [
                10,
                9,
                8,
                3,
                1,
                0,
            ],
        ]);
        $foxTypeTarget = CompetitionTypeTargetFactory::createOne([
            'competitionType' => $type,
            'displayOrder' => 1,
            'shotCount' => 10,
            'targetDefinition' => $foxTarget,
            'tieBreakPriority' => 1,
        ]);
        $type->addTarget($foxTypeTarget);

        $roeBuckTarget = TargetDefinitionFactory::createOne([
            'name' => 'Srnec - Terč medzinárodný redukovaný na 50m',
            'shortName' => 'Srnec',
            'pointsSchema' => [
                10,
                9,
                8,
                3,
                1,
                0,
            ],
        ]);
        $roeBuckTypeTarget = CompetitionTypeTargetFactory::createOne([
            'competitionType' => $type,
            'displayOrder' => 2,
            'shotCount' => 10,
            'targetDefinition' => $roeBuckTarget,
            'tieBreakPriority' => 2,
        ]);
        $type->addTarget($roeBuckTypeTarget);

        $chamoisTarget = TargetDefinitionFactory::createOne([
            'name' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
            'shortName' => 'Kamzík',
            'pointsSchema' => [
                10,
                9,
                8,
                3,
                1,
                0,
            ],
        ]);
        $chamoisTypeTarget = CompetitionTypeTargetFactory::createOne([
            'competitionType' => $type,
            'displayOrder' => 3,
            'shotCount' => 10,
            'targetDefinition' => $chamoisTarget,
            'tieBreakPriority' => 3,
        ]);
        $type->addTarget($chamoisTypeTarget);

        $boarTarget = TargetDefinitionFactory::createOne([
            'name' => 'Diviak - Terč medzinárodný redukovaný na 50m',
            'shortName' => 'Diviak',
            'pointsSchema' => [
                10,
                9,
                8,
                5,
                3,
                0,
            ],
        ]);
        $boarTypeTarget = CompetitionTypeTargetFactory::createOne([
            'competitionType' => $type,
            'displayOrder' => 4,
            'shotCount' => 10,
            'targetDefinition' => $boarTarget,
            'tieBreakPriority' => 4,
        ]);
        $type->addTarget($boarTypeTarget);

        save($type);
    }
}
