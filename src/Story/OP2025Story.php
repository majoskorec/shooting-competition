<?php

declare(strict_types=1);

namespace App\Story;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Model\CompetitorStatus;
use App\Competition\Target\TargetSnapshotFactory;
use App\Entity\Competition;
use App\Entity\CompetitionCategory;
use App\Entity\CompetitionTeam;
use App\Entity\CompetitionType;
use App\Entity\Shooter;
use App\Tests\Factory\CompetitionCategoryFactory;
use App\Tests\Factory\CompetitionFactory;
use App\Tests\Factory\CompetitionTeamFactory;
use App\Tests\Factory\CompetitionTypeFactory;
use App\Tests\Factory\CompetitorFactory;
use App\Tests\Factory\ShooterFactory;
use App\Tests\Factory\TargetResultFactory;
use DateTimeImmutable;
use Override;
use RuntimeException;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\save;

#[AsFixture(name: 'op2025')]
final class OP2025Story extends Story
{
    // phpcs:disable SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys.IncorrectKeyOrder
    private const array COMPETITORS = [
        [
            'startNumber' => 1,
            'firstName' => 'Matrián',
            'lastName' => 'Lehotský',
            'club' => 'PZ Grúň',
            'teamName' => 'PZ Grúň 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 4,
                        8 => 3,
                        3 => 3,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 3,
                        3 => 0,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 2,
            'firstName' => 'Jaroslav',
            'lastName' => 'Melich',
            'club' => 'UPS Hybe',
            'teamName' => 'UPS Hybe',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 2,
                        3 => 3,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 5,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 3,
            'firstName' => 'Roman',
            'lastName' => 'Dermek',
            'club' => 'PZ Baranec',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 4,
            'firstName' => 'Peter',
            'lastName' => 'Hrtko',
            'club' => 'PZ Ráztoka',
            'teamName' => 'PZ Ráztoka',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 1,
                        5 => 2,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 5,
            'firstName' => 'Martin',
            'lastName' => 'Hladký',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        3 => 2,
                        1 => 2,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 0,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 6,
            'firstName' => 'Eva',
            'lastName' => 'Čendulová',
            'club' => null,
            'teamName' => 'PZ Viacov ženy',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 0,
                        3 => 4,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 2,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        3 => 4,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 7,
            'firstName' => 'Jozef',
            'lastName' => 'Hladký',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 5,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 8,
            'firstName' => 'Vladimír',
            'lastName' => 'Kabát',
            'club' => 'PZ Grúň Východná',
            'teamName' => 'PZ Grúň 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 0,
                        8 => 5,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        5 => 3,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 9,
            'firstName' => 'Adam',
            'lastName' => 'Šramo',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 10,
            'firstName' => 'Daniel',
            'lastName' => 'Kočtúch',
            'club' => 'Svätojánska dolina 1',
            'teamName' => 'Svätojánska dolina 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 0,
                        3 => 3,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 5,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 11,
            'firstName' => 'Nela',
            'lastName' => 'Bubniaková',
            'club' => null,
            'teamName' => 'PZ Viacov ženy',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 6,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 6,
                        5 => 0,
                        3 => 1,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 12,
            'firstName' => 'Luka',
            'lastName' => 'Hladký',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 5,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 1,
                        3 => 3,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 13,
            'firstName' => 'Patrik',
            'lastName' => 'Hronček',
            'club' => 'OPK LM',
            'teamName' => 'OPK LM',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 2,
                        3 => 1,
                        1 => 2,
                        0 => 5,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 2,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        5 => 1,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 14,
            'firstName' => 'Radoslav',
            'lastName' => 'Bobula',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 6,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 5,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 15,
            'firstName' => 'Ivan',
            'lastName' => 'Pozor',
            'club' => 'PR Bystrá',
            'teamName' => 'PR Bystrá',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 0,
                        3 => 2,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 5,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 16,
            'firstName' => 'Peter',
            'lastName' => 'Melich',
            'club' => 'UPS Hybe',
            'teamName' => 'UPS Hybe',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        5 => 2,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 17,
            'firstName' => 'Martina',
            'lastName' => 'Hegedušová',
            'club' => 'PZ Lupčianka',
            'teamName' => null,
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 0,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 6,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 18,
            'firstName' => 'Nikol',
            'lastName' => 'Čendulová',
            'club' => null,
            'teamName' => 'PZ Viacov ženy',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 1,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 4,
                        8 => 4,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 19,
            'firstName' => 'Jozef',
            'lastName' => 'Čendula',
            'club' => 'PZ Viackov',
            'teamName' => 'PZ Viackov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 0,
                        3 => 2,
                        1 => 2,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 20,
            'firstName' => 'Hugo',
            'lastName' => 'Šimanský',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 0,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 5,
                        8 => 0,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 21,
            'firstName' => 'Pavel',
            'lastName' => 'Bizub',
            'club' => 'PZ Stará dolina',
            'teamName' => 'PZ Stará dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 0,
                        3 => 3,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 2,
                        3 => 4,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 4,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 22,
            'firstName' => 'Ladislav',
            'lastName' => 'Zvara',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 1',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 0,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 1,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 0,
                        8 => 3,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 0,
                        8 => 3,
                        5 => 2,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 23,
            'firstName' => 'Anna',
            'lastName' => 'Škorupová',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 2',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 0,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 4,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 1,
                        3 => 3,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 24,
            'firstName' => 'Michal',
            'lastName' => 'Uličný',
            'club' => 'PZ Baranec',
            'teamName' => 'PZ Baranec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 25,
            'firstName' => 'Branislav',
            'lastName' => 'Šramo',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 2',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 0,
                        8 => 2,
                        3 => 2,
                        1 => 3,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 2,
                        5 => 2,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 26,
            'firstName' => 'Martin',
            'lastName' => 'Pavella',
            'club' => null,
            'teamName' => 'PZ Grúň 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        3 => 2,
                        1 => 2,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 27,
            'firstName' => 'Dávid',
            'lastName' => 'Koreň',
            'club' => 'Svätojánska dolina 2',
            'teamName' => 'Svätojánska dolina 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 2,
                        0 => 6,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 5,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 28,
            'firstName' => 'Martin',
            'lastName' => 'Bartík',
            'club' => null,
            'teamName' => 'Ľupčianka',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 5,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 29,
            'firstName' => 'Martin',
            'lastName' => 'Droppa',
            'club' => 'PZ Siná',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 3,
                        3 => 0,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 30,
            'firstName' => 'Martin',
            'lastName' => 'Richter',
            'club' => 'PZ Baranec',
            'teamName' => 'PZ Baranec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 31,
            'firstName' => 'Juraj',
            'lastName' => 'Janičina',
            'club' => 'PZ Viackov',
            'teamName' => 'PZ Viackov',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 5,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 4,
                        3 => 3,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 5,
                        8 => 0,
                        5 => 2,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 32,
            'firstName' => 'Adam',
            'lastName' => 'Jurena',
            'club' => 'PZ Kriváň',
            'teamName' => 'PZ Kriváň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 2,
                        3 => 3,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 1,
                        5 => 1,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 33,
            'firstName' => 'Miroslav',
            'lastName' => 'Labaj',
            'club' => 'PS Prosečné',
            'teamName' => 'PS Prosečné',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 0,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 7,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 2,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 1,
                        5 => 0,
                        3 => 1,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 34,
            'firstName' => 'Pavel',
            'lastName' => 'Vechter',
            'club' => 'PZ Grúň Východná',
            'teamName' => 'PZ Grúň 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 35,
            'firstName' => 'Martin',
            'lastName' => 'Rajniak',
            'club' => 'UPS Hybe',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 6,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 36,
            'firstName' => 'Michal',
            'lastName' => 'Král',
            'club' => 'PZ Lupčianka',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 7,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 3,
                        5 => 1,
                        3 => 2,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 37,
            'firstName' => 'Michal',
            'lastName' => 'Michalíček',
            'club' => 'PZ Kriváň',
            'teamName' => 'PZ Kriváň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 4,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 2,
                        3 => 3,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 38,
            'firstName' => 'Dušan',
            'lastName' => 'Jurík',
            'club' => null,
            'teamName' => 'UPS Hybe',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 5,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 39,
            'firstName' => 'Ján',
            'lastName' => 'Lukačko',
            'club' => 'OPK LM',
            'teamName' => 'OPK LM',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 0,
                        3 => 5,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 5,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 40,
            'firstName' => 'Roman',
            'lastName' => 'Priesol',
            'club' => null,
            'teamName' => 'Ľupčianka',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 1,
                        5 => 2,
                        3 => 1,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 41,
            'firstName' => 'Peter',
            'lastName' => 'Bobák',
            'club' => 'PS Prosečné',
            'teamName' => 'PS Prosečné',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 1,
                        3 => 3,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 42,
            'firstName' => 'Vladimír',
            'lastName' => 'Strapoň',
            'club' => 'PR Bystrá',
            'teamName' => 'PR Bystrá',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 1,
                        3 => 3,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 5,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 43,
            'firstName' => 'Marek',
            'lastName' => 'Šimanský',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 44,
            'firstName' => 'Štefan',
            'lastName' => 'Jurčo',
            'club' => null,
            'teamName' => 'PZ Grúň 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 0,
                        3 => 5,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 2,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 45,
            'firstName' => 'Martin',
            'lastName' => 'Hubka',
            'club' => null,
            'teamName' => 'D. Dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 0,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 46,
            'firstName' => 'Vladimír',
            'lastName' => 'Volaj',
            'club' => 'PZ Ráztoka',
            'teamName' => 'PZ Ráztoka',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 4,
                        5 => 1,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 47,
            'firstName' => 'František',
            'lastName' => 'Mrázik',
            'club' => 'PZ Chabenec',
            'teamName' => 'PZ Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 5,
                        8 => 1,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 48,
            'firstName' => 'Ľubomír',
            'lastName' => 'Uličný',
            'club' => 'PZ Baranec',
            'teamName' => 'PZ Baranec',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 49,
            'firstName' => 'Filip',
            'lastName' => 'Slabý',
            'club' => 'OPK LM',
            'teamName' => 'OPK LM',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 5,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 50,
            'firstName' => 'Ján',
            'lastName' => 'Capko',
            'club' => 'Svätojánska dolina 1',
            'teamName' => 'Svätojánska dolina',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 7,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 51,
            'firstName' => 'Vladimír',
            'lastName' => 'Hladký',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 52,
            'firstName' => 'Peter',
            'lastName' => 'Mrázik',
            'club' => 'PZ Chabenec',
            'teamName' => 'PZ Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 0,
                        3 => 5,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 4,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 1,
                        3 => 2,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 3,
                        5 => 1,
                        3 => 1,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 53,
            'firstName' => 'Jozef',
            'lastName' => 'Škorupa',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 2',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 8,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 54,
            'firstName' => 'Pavel',
            'lastName' => 'Kočtúch',
            'club' => 'Bukovica',
            'teamName' => null,
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 1,
                        3 => 2,
                        1 => 2,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 55,
            'firstName' => 'Andrej',
            'lastName' => 'Michalíček',
            'club' => 'PZ Kriváň',
            'teamName' => 'PZ Kriváň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 8,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 0,
                        3 => 2,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        5 => 1,
                        3 => 2,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 56,
            'firstName' => 'Jakub',
            'lastName' => 'Fiedor',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 2,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 57,
            'firstName' => 'Ján',
            'lastName' => 'Batiz',
            'club' => 'PZ Poludnica',
            'teamName' => 'PZ Poludnica 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 58,
            'firstName' => 'Erik',
            'lastName' => 'Bubniak',
            'club' => 'PZ Viackov',
            'teamName' => 'PZ Viackov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        3 => 3,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 1,
                        5 => 1,
                        3 => 1,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 59,
            'firstName' => 'Cyril',
            'lastName' => 'Bebko',
            'club' => 'PZ Siná',
            'teamName' => 'PZ Siná',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 0,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 4,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 60,
            'firstName' => 'Lukáš',
            'lastName' => 'Brezina',
            'club' => 'PZ Grúň Východná',
            'teamName' => 'PZ Grúň 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 0,
                        3 => 2,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 3,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 61,
            'firstName' => 'Peter',
            'lastName' => 'Kocúr',
            'club' => 'PS Prosečné',
            'teamName' => 'PS Prosečné',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 2,
                        3 => 0,
                        1 => 3,
                        0 => 5,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 0,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 8,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 62,
            'firstName' => 'Ivan',
            'lastName' => 'Beharka',
            'club' => 'PR Bystrá',
            'teamName' => 'PR Bystrá',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 4,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 63,
            'firstName' => 'Ľuboš',
            'lastName' => 'Kočtúch st.',
            'club' => 'Bukovica',
            'teamName' => 'Svätojánska dolina 2',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 3,
                        3 => 1,
                        1 => 2,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 64,
            'firstName' => 'Dušan',
            'lastName' => 'Palko',
            'club' => 'PZ Siná',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 4,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 65,
            'firstName' => 'Anton',
            'lastName' => 'Kováč',
            'club' => 'Svätojánska dolina 2',
            'teamName' => 'Svätojánska dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        5 => 2,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 66,
            'firstName' => 'Martina',
            'lastName' => 'Papajová',
            'club' => null,
            'teamName' => 'Hradská hora',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 5,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 67,
            'firstName' => 'Ľuboš',
            'lastName' => 'Kočtúch ml.',
            'club' => 'Svätojánska dolina 1',
            'teamName' => 'Svätojánska dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 5,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 6,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 2,
                        5 => 1,
                        3 => 1,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 68,
            'firstName' => 'Jaroslav',
            'lastName' => 'Pauko',
            'club' => 'PZ Siná',
            'teamName' => 'PZ Siná',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 10,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 69,
            'firstName' => 'Matej',
            'lastName' => 'Ondrejka',
            'club' => null,
            'teamName' => 'Ľupčianka',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 1,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 70,
            'firstName' => 'Michal',
            'lastName' => 'Nemec',
            'club' => 'PZ Chabenec',
            'teamName' => 'PZ Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 2,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 2,
                        3 => 2,
                        1 => 2,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 4,
                        5 => 1,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 71,
            'firstName' => 'Ondrej',
            'lastName' => 'Jaňák',
            'club' => 'PZ Ostrô',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 3,
                        3 => 1,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 2,
                        5 => 3,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 72,
            'firstName' => 'Ivan',
            'lastName' => 'Fiačan',
            'club' => 'PZ Siná',
            'teamName' => 'PZ Siná',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 6,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 73,
            'firstName' => 'Aneta',
            'lastName' => 'Pačesová',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 1',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 1,
                        3 => 2,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 74,
            'firstName' => 'Ján',
            'lastName' => 'Staroň ml.',
            'club' => null,
            'teamName' => 'Baníkov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        3 => 6,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 3,
                        5 => 2,
                        3 => 1,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 75,
            'firstName' => 'Marek',
            'lastName' => 'Repčík',
            'club' => null,
            'teamName' => 'Baníkov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 4,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 3,
                        5 => 1,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 76,
            'firstName' => 'Milan',
            'lastName' => 'Zvara ml.',
            'club' => null,
            'teamName' => 'Baníkov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 1,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 0,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 2,
                        3 => 3,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 77,
            'firstName' => 'Jozef',
            'lastName' => 'Gracík',
            'club' => null,
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 4,
                        5 => 1,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 78,
            'firstName' => 'Marián',
            'lastName' => 'Polóni',
            'club' => null,
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 1,
                        3 => 9,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 0,
                        5 => 0,
                        3 => 1,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 79,
            'firstName' => 'Rastislav',
            'lastName' => 'Zajden',
            'club' => null,
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 4,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 2,
                        5 => 2,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 80,
            'firstName' => 'Jaroslav',
            'lastName' => 'Surovček',
            'club' => null,
            'teamName' => 'Hradská hora',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 1,
                        3 => 4,
                        1 => 1,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 4,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 81,
            'firstName' => 'Jaroslav',
            'lastName' => 'Papaj',
            'club' => null,
            'teamName' => 'Hradská hora',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 3,
                        3 => 1,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 9,
                        9 => 0,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 3,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
    ];
    // phpcs:enable SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys.IncorrectKeyOrder

    public function __construct(
        private readonly TargetSnapshotFactory $targetSnapshotFactory,
    ) {
    }

    #[Override]
    public function build(): void
    {
        $competitionType = $this->getOrCreateM400CompetitionType();

        $competition = CompetitionFactory::createOne([
            'competitionStart' => new DateTimeImmutable('2025-05-24 07:00:00'),
            'competitionType' => $competitionType,
            'location' => 'Strelnica Dovalovo',
            'mainCategoryName' => 'Memoriál Antona Krištofa',
            'name' => 'Majstrovstvá okresu 2025',
            'organizer' => 'OPK LM',
            'shootersInRound' => 9,
            'status' => CompetitionStatus::Finished,
            'targetConfigurationSnapshot' => $this->targetSnapshotFactory->createFromCompetitionType($competitionType),
            'teamMemberCount' => 3,
        ]);

        $veteranCategory = $this->createCategory($competition, 'Veteráni');
        $seniorCategory = $this->createCategory($competition, 'Seniori');
        $womanCategory = $this->createCategory($competition, 'Ženy');
        $teams = [];

        foreach (self::COMPETITORS as $competitorData) {
            $shooter = $this->getOrCreateShooter($competitorData['firstName'], $competitorData['lastName'], $competitorData['club']);

            $competitor = CompetitorFactory::createOne([
                'competition' => $competition,
                'competitionTeam' => $this->getOrCreateTeam($competition, $competitorData['teamName'], $teams),
                'shooter' => $shooter,
                'startNumber' => $competitorData['startNumber'],
                'status' => CompetitorStatus::Registered,
            ]);

            if ($competitorData['veteran']) {
                $competitor->addCategory($veteranCategory);
            }
            if (!$competitorData['veteran']) {
                $competitor->addCategory($seniorCategory);
            }
            if ($competitorData['woman']) {
                $competitor->addCategory($womanCategory);
            }
            save($competitor);

            foreach ($competitorData['targetResults'] as $targetResultData) {
                TargetResultFactory::createOne([
                    'competitor' => $competitor,
                    'hitBreakdown' => $targetResultData['hitBreakdown'],
                    'targetName' => $targetResultData['targetName'],
                ]);
            }

            $competition->addCompetitor($competitor);
        }

        save($competition);
    }

    private function getOrCreateM400CompetitionType(): CompetitionType
    {
        $competitionType = CompetitionTypeFactory::repository()->findOneBy(['name' => 'M400']);
        if ($competitionType instanceof CompetitionType) {
            return $competitionType;
        }

        M400TypeStory::load();

        $competitionType = CompetitionTypeFactory::repository()->findOneBy(['name' => 'M400']);
        if (!$competitionType instanceof CompetitionType) {
            throw new RuntimeException('CompetitionType M400 was not created.');
        }

        return $competitionType;
    }

    private function createCategory(Competition $competition, string $name): CompetitionCategory
    {
        return CompetitionCategoryFactory::createOne([
            'competition' => $competition,
            'name' => $name,
        ]);
    }

    private function getOrCreateShooter(string $firstName, string $lastName, ?string $club): Shooter
    {
        $shooter = ShooterFactory::repository()->findOneBy([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);

        if (!$shooter instanceof Shooter) {
            return ShooterFactory::createOne([
                'club' => $club,
                'email' => null,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        if ($club !== null) {
            $shooter->setClub($club);
            save($shooter);
        }

        return $shooter;
    }

    /**
     * @param array<string, CompetitionTeam> $teams
     */
    private function getOrCreateTeam(Competition $competition, ?string $teamName, array &$teams): ?CompetitionTeam
    {
        if ($teamName === null) {
            return null;
        }

        $team = $teams[$teamName] ?? null;
        if ($team instanceof CompetitionTeam) {
            return $team;
        }

        $team = CompetitionTeamFactory::createOne([
            'competition' => $competition,
            'name' => $teamName,
        ]);

        $teams[$teamName] = $team;

        return $team;
    }
}
