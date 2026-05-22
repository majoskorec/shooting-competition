<?php

declare(strict_types=1);

namespace App\Story;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Model\CompetitorStatus;
use App\Competition\Target\TargetSnapshotFactory;
use App\Entity\Competition;
use App\Entity\CompetitionCategory;
use App\Entity\CompetitionCategoryRule;
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

#[AsFixture(name: 'op2024')]
final class OP2024Story extends Story
{
    // phpcs:disable SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys.IncorrectKeyOrder
    private const array COMPETITORS = [
        [
            'startNumber' => 1,
            'firstName' => 'Anton',
            'lastName' => 'Valíček',
            'club' => 'PZ Grúň',
            'teamName' => 'PZ Grúň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 4,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                        10 => 1,
                        9 => 3,
                        8 => 1,
                        5 => 1,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 2,
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
                        10 => 7,
                        9 => 2,
                        8 => 0,
                        3 => 1,
                        1 => 0,
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
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 3,
            'firstName' => 'Ivan',
            'lastName' => 'Gracík',
            'club' => 'Čertovica',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                        9 => 4,
                        8 => 1,
                        5 => 0,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 4,
            'firstName' => 'Ján',
            'lastName' => 'Capko',
            'club' => 'PZ Svätojánska Dolina',
            'teamName' => 'PZ Svätojánska Dolina',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                        10 => 3,
                        9 => 4,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 5,
            'firstName' => 'Tibor',
            'lastName' => 'Nagy',
            'club' => 'Siná',
            'teamName' => 'Siná 1',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                        10 => 3,
                        9 => 7,
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
                        5 => 2,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 6,
            'firstName' => 'Daniel',
            'lastName' => 'Kočtúch',
            'club' => 'PZ Svätojánska Dolina',
            'teamName' => 'PZ Svätojánska Dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 2,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
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
            'startNumber' => 7,
            'firstName' => 'Roald',
            'lastName' => 'Tretiník',
            'club' => null,
            'teamName' => null,
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 10,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 10,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 8,
            'firstName' => 'Ivan',
            'lastName' => 'Fiačan',
            'club' => 'Siná',
            'teamName' => 'Siná 2',
            'veteran' => true,
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
                        9 => 2,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 1,
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
                        10 => 1,
                        9 => 5,
                        8 => 2,
                        5 => 2,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 9,
            'firstName' => 'Marek',
            'lastName' => 'Šimanský',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 1',
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
                        10 => 5,
                        9 => 1,
                        8 => 4,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 10,
            'firstName' => 'Vladimír',
            'lastName' => 'Strapoň',
            'club' => 'PSBU Pribylina',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                        10 => 0,
                        9 => 3,
                        8 => 1,
                        5 => 2,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 11,
            'firstName' => 'Andrej',
            'lastName' => 'Michalíček',
            'club' => 'Kriváň',
            'teamName' => 'Kriváň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 2,
                        0 => 5,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        3 => 4,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 3,
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
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 12,
            'firstName' => 'Marián',
            'lastName' => 'Polóni',
            'club' => 'Čertovica',
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                        10 => 0,
                        9 => 2,
                        8 => 3,
                        5 => 3,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 13,
            'firstName' => 'Jaroslav',
            'lastName' => 'Melich',
            'club' => null,
            'teamName' => 'UPS Hybe',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 14,
            'firstName' => 'Peter',
            'lastName' => 'Bobák',
            'club' => 'Prosečné',
            'teamName' => null,
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
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 3,
                        3 => 1,
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
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 3,
                        5 => 2,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 15,
            'firstName' => 'Martina',
            'lastName' => 'Papajová',
            'club' => 'Hradská Hora',
            'teamName' => 'Hradská Hora',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 0,
                        3 => 4,
                        1 => 4,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
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
                        9 => 3,
                        8 => 4,
                        3 => 0,
                        1 => 0,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 4,
                        8 => 1,
                        5 => 3,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 16,
            'firstName' => 'Vladimír',
            'lastName' => 'Poliak',
            'club' => 'Chabenec',
            'teamName' => 'Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 1,
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
                        10 => 3,
                        9 => 3,
                        8 => 4,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 6,
                        5 => 0,
                        3 => 1,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 17,
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
                        10 => 4,
                        9 => 0,
                        8 => 4,
                        3 => 0,
                        1 => 0,
                        0 => 2,
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
                        10 => 5,
                        9 => 3,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 5,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 18,
            'firstName' => 'Peter',
            'lastName' => 'Mrázik',
            'club' => 'Chabenec',
            'teamName' => 'Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 4,
                        5 => 1,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 19,
            'firstName' => 'Martin',
            'lastName' => 'Droppa',
            'club' => 'Siná',
            'teamName' => 'Siná 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
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
                        10 => 10,
                        9 => 0,
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
                        9 => 2,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 20,
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
                        10 => 0,
                        9 => 0,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 9,
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
                        10 => 5,
                        9 => 3,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 21,
            'firstName' => 'Pavel',
            'lastName' => 'Bizub',
            'club' => null,
            'teamName' => 'Stará Dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 0,
                        3 => 0,
                        1 => 0,
                        0 => 10,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 3,
                        3 => 1,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 0,
                        3 => 2,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 1,
                        5 => 1,
                        3 => 1,
                        0 => 7,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 22,
            'firstName' => 'Pavel',
            'lastName' => 'Kočtúch',
            'club' => 'Brtkovica',
            'teamName' => 'Brtkovica',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 2,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 4,
                        8 => 0,
                        5 => 1,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 23,
            'firstName' => 'Ľuboš',
            'lastName' => 'Kočtúch ml.',
            'club' => 'Brtkovica',
            'teamName' => 'Brtkovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 0,
                        8 => 1,
                        3 => 2,
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
                        9 => 0,
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
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 24,
            'firstName' => 'Marek',
            'lastName' => 'Callo',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 3,
                        3 => 0,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 0,
                        3 => 3,
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
                        3 => 1,
                        1 => 2,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 4,
                        5 => 0,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 25,
            'firstName' => 'Martin',
            'lastName' => 'Škorupa',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 0,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 1,
                        3 => 0,
                        1 => 1,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        5 => 2,
                        3 => 1,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 26,
            'firstName' => 'Miloš',
            'lastName' => 'Valach',
            'club' => 'Kriváň',
            'teamName' => null,
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
                        10 => 0,
                        9 => 5,
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
                        9 => 2,
                        8 => 5,
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
                        8 => 1,
                        5 => 1,
                        3 => 0,
                        0 => 7,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 27,
            'firstName' => 'Ján',
            'lastName' => 'Lukačko',
            'club' => null,
            'teamName' => null,
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 3,
                        8 => 1,
                        3 => 2,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 4,
                        8 => 2,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 0,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 28,
            'firstName' => 'Jakub',
            'lastName' => 'Fiedor',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 0,
                        3 => 4,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 3,
                        8 => 0,
                        5 => 0,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 29,
            'firstName' => 'Ján',
            'lastName' => 'Jaňák',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 2',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 2,
                        3 => 1,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
                        8 => 1,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 6,
                        8 => 0,
                        3 => 0,
                        1 => 2,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 4,
                        5 => 1,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 30,
            'firstName' => 'Jaroslav',
            'lastName' => 'Pauko',
            'club' => 'Siná',
            'teamName' => 'Siná 1',
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
                        10 => 5,
                        9 => 3,
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
                        9 => 1,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 5,
                        8 => 3,
                        5 => 2,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 32,
            'firstName' => 'Adam',
            'lastName' => 'Jurena',
            'club' => 'Kriváň',
            'teamName' => 'Kriváň',
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
                        1 => 2,
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
                        9 => 1,
                        8 => 2,
                        5 => 0,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 33,
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
                        10 => 3,
                        9 => 3,
                        8 => 3,
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
                        9 => 3,
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
                        9 => 0,
                        8 => 2,
                        5 => 3,
                        3 => 1,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 34,
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
                        10 => 4,
                        9 => 1,
                        8 => 2,
                        3 => 3,
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
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 35,
            'firstName' => 'Cyril',
            'lastName' => 'Bebko',
            'club' => 'Siná',
            'teamName' => 'Siná 1',
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
                        10 => 10,
                        9 => 0,
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
                        9 => 5,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 36,
            'firstName' => 'Lukáš',
            'lastName' => 'Brezina',
            'club' => 'PZ Grúň',
            'teamName' => 'PZ Grúň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 0,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 1,
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
            'startNumber' => 37,
            'firstName' => 'Martin',
            'lastName' => 'Skaličan',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 1',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 5,
                        8 => 0,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 4,
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
                        8 => 5,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 38,
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
                        10 => 5,
                        9 => 3,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 8,
                        9 => 0,
                        8 => 2,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 5,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 39,
            'firstName' => 'Ľuboš',
            'lastName' => 'Kočtúch st.',
            'club' => 'Brtkovica',
            'teamName' => 'Brtkovica',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 1,
                        8 => 0,
                        3 => 1,
                        1 => 3,
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
                        10 => 2,
                        9 => 6,
                        8 => 1,
                        3 => 0,
                        1 => 1,
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
            'startNumber' => 40,
            'firstName' => 'Ondrej',
            'lastName' => 'Jaňák',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 2',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 0,
                        8 => 4,
                        3 => 0,
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
                        5 => 0,
                        3 => 0,
                        0 => 6,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 41,
            'firstName' => 'Roman',
            'lastName' => 'Dermek',
            'club' => 'Baranec',
            'teamName' => 'Baranec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 2,
                        8 => 0,
                        3 => 1,
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
                        10 => 4,
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 3,
                        5 => 2,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 42,
            'firstName' => 'Peter',
            'lastName' => 'Welnitz',
            'club' => 'Hradská Hora',
            'teamName' => 'Hradská Hora',
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
                        1 => 4,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 4,
                        3 => 2,
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
                        3 => 0,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 2,
                        8 => 1,
                        5 => 1,
                        3 => 1,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 43,
            'firstName' => 'Michal',
            'lastName' => 'Michalíček',
            'club' => 'Kriváň',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        3 => 5,
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
                        10 => 1,
                        9 => 3,
                        8 => 5,
                        3 => 0,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 7,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 44,
            'firstName' => 'Ľubomír',
            'lastName' => 'Uličný',
            'club' => 'Baranec',
            'teamName' => 'Baranec',
            'veteran' => true,
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
                        10 => 7,
                        9 => 0,
                        8 => 3,
                        3 => 0,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 5,
                        8 => 0,
                        5 => 1,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 45,
            'firstName' => 'Jozef',
            'lastName' => 'Škorupa',
            'club' => 'PZ Belanská',
            'teamName' => null,
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 1,
                        3 => 0,
                        1 => 0,
                        0 => 7,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 2,
                        8 => 5,
                        3 => 1,
                        1 => 1,
                        0 => 1,
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
            'startNumber' => 46,
            'firstName' => 'Lea',
            'lastName' => 'Michalíčková',
            'club' => 'Kriváň',
            'teamName' => 'Kriváň',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 1,
                        3 => 1,
                        1 => 2,
                        0 => 5,
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
                        10 => 2,
                        9 => 2,
                        8 => 3,
                        3 => 2,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 1,
                        5 => 1,
                        3 => 0,
                        0 => 7,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 47,
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
                        9 => 2,
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
                        9 => 4,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 48,
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
                        10 => 2,
                        9 => 1,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 1,
                        3 => 0,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 2,
                        3 => 1,
                        1 => 1,
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
            'startNumber' => 49,
            'firstName' => 'František',
            'lastName' => 'Mrázik',
            'club' => 'Chabenec',
            'teamName' => 'Chabenec',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 1,
                        3 => 3,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 1,
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
                        10 => 1,
                        9 => 5,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 50,
            'firstName' => 'Jaroslav',
            'lastName' => 'Repčík',
            'club' => 'Baníkov',
            'teamName' => 'Baníkov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 0,
                        3 => 3,
                        1 => 0,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 7,
                        9 => 1,
                        8 => 0,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 3,
                        8 => 5,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 8,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 51,
            'firstName' => 'Vladimír',
            'lastName' => 'Volaj',
            'club' => 'PZ Ráztoka',
            'teamName' => null,
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 0,
                        3 => 2,
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
                        10 => 8,
                        9 => 1,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 6,
                        9 => 2,
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
            'firstName' => 'Anton',
            'lastName' => 'Kováč',
            'club' => 'PZ Svätojánska Dolina',
            'teamName' => 'PZ Svätojánska Dolina',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 1,
                        3 => 1,
                        1 => 1,
                        0 => 3,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 4,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 0,
                        3 => 3,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 1,
                        8 => 1,
                        5 => 3,
                        3 => 0,
                        0 => 3,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 53,
            'firstName' => 'Vladimír',
            'lastName' => 'Kabát',
            'club' => 'PZ Grúň',
            'teamName' => 'PZ Grúň',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 4,
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
                        10 => 2,
                        9 => 4,
                        8 => 1,
                        5 => 2,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 54,
            'firstName' => 'Rastislav',
            'lastName' => 'Zajden',
            'club' => 'Čertovica',
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 1,
                        8 => 2,
                        3 => 2,
                        1 => 0,
                        0 => 2,
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
                        9 => 4,
                        8 => 3,
                        5 => 1,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 55,
            'firstName' => 'Ján',
            'lastName' => 'Staroň',
            'club' => 'Baníkov',
            'teamName' => 'Baníkov',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 3,
                        9 => 3,
                        8 => 1,
                        3 => 2,
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
                        9 => 4,
                        8 => 2,
                        5 => 1,
                        3 => 1,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 56,
            'firstName' => 'Martin',
            'lastName' => 'Richter',
            'club' => 'Baranec',
            'teamName' => 'Baranec',
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
            'startNumber' => 57,
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
                        10 => 3,
                        9 => 5,
                        8 => 0,
                        3 => 2,
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
                        10 => 7,
                        9 => 1,
                        8 => 0,
                        3 => 1,
                        1 => 1,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 1,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 5,
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
                        3 => 0,
                        1 => 2,
                        0 => 4,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 4,
                        8 => 0,
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
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 2,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 2,
                        5 => 0,
                        3 => 3,
                        0 => 4,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 59,
            'firstName' => 'Aneta',
            'lastName' => 'Pačesová',
            'club' => 'PZ Belanská',
            'teamName' => 'PZ Belanská 2',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 0,
                        8 => 4,
                        3 => 0,
                        1 => 2,
                        0 => 4,
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
                        9 => 2,
                        8 => 2,
                        3 => 1,
                        1 => 1,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 4,
                        9 => 2,
                        8 => 2,
                        5 => 0,
                        3 => 1,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 60,
            'firstName' => 'Dušan',
            'lastName' => 'Palko',
            'club' => 'Siná',
            'teamName' => 'Siná 2',
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
                        10 => 8,
                        9 => 1,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 0,
                        9 => 1,
                        8 => 4,
                        5 => 0,
                        3 => 0,
                        0 => 5,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 61,
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
                        10 => 9,
                        9 => 0,
                        8 => 1,
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
                        10 => 0,
                        9 => 8,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 62,
            'firstName' => 'Pavel',
            'lastName' => 'Repčík',
            'club' => 'Baníkov',
            'teamName' => 'Baníkov',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
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
                        10 => 2,
                        9 => 5,
                        8 => 2,
                        5 => 1,
                        3 => 0,
                        0 => 0,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 63,
            'firstName' => 'Jozef',
            'lastName' => 'Gracík',
            'club' => 'Čertovica',
            'teamName' => 'Čertovica',
            'veteran' => false,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 2,
                        8 => 1,
                        3 => 2,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 3,
                        8 => 1,
                        5 => 0,
                        3 => 0,
                        0 => 1,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 64,
            'firstName' => 'Zuzana',
            'lastName' => 'Jaňáková',
            'club' => 'PZ Ostrô',
            'teamName' => 'PZ Ostrô 2',
            'veteran' => false,
            'woman' => true,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
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
                    'targetName' => 'Srnec - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 4,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Kamzík - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 2,
                        9 => 2,
                        8 => 3,
                        3 => 2,
                        1 => 0,
                        0 => 1,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 3,
                        8 => 1,
                        5 => 2,
                        3 => 1,
                        0 => 2,
                    ],
                ],
            ],
        ],
        [
            'startNumber' => 65,
            'firstName' => 'Jaroslav',
            'lastName' => 'Papaj',
            'club' => 'Hradská Hora',
            'teamName' => 'Hradská Hora',
            'veteran' => true,
            'woman' => false,
            'targetResults' => [
                [
                    'targetName' => 'Líška - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 5,
                        9 => 0,
                        8 => 2,
                        3 => 2,
                        1 => 1,
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
                        10 => 7,
                        9 => 2,
                        8 => 0,
                        3 => 1,
                        1 => 0,
                        0 => 0,
                    ],
                ],
                [
                    'targetName' => 'Diviak - Terč medzinárodný redukovaný na 50m',
                    'hitBreakdown' => [
                        10 => 1,
                        9 => 5,
                        8 => 3,
                        5 => 0,
                        3 => 0,
                        0 => 1,
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
            'competitionStart' => new DateTimeImmutable('2024-05-18 07:00:00'),
            'competitionType' => $competitionType,
            'location' => 'Strelnica Dovalovo',
            'mainCategoryName' => 'Memoriál Antona Krištofa',
            'name' => 'Majstrovstvá okresu 2024',
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
        $rule = match ($name) {
            'Seniori' => CompetitionCategoryRule::MenSeniors,
            'Veteráni' => CompetitionCategoryRule::MenVeterans,
            'Ženy' => CompetitionCategoryRule::Women,
            default => null,
        };

        return CompetitionCategoryFactory::createOne([
            'competition' => $competition,
            'name' => $name,
            'rule' => $rule,
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
