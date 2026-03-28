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
use App\Entity\Competitor;
use App\Entity\Shooter;
use App\Entity\TargetResult;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use RuntimeException;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'op2024')]
final class OP2024Story extends Story
{
    private const array COMPETITORS = [
    [
        'startNumber' => 1,
        'firstName' => 'Anton',
        'lastName' => 'Valíček',
        'club' => 'PZ Grúň',
        'teamName' => 'PZ Grúň',
        'cachedTotalScore' => 313,
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
        'cachedTotalScore' => 358,
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
        'cachedTotalScore' => 309,
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
        'cachedTotalScore' => 367,
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
        'cachedTotalScore' => 348,
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
        'cachedTotalScore' => 301,
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
        'cachedTotalScore' => 115,
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
        'cachedTotalScore' => 350,
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
        'cachedTotalScore' => 379,
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
        'cachedTotalScore' => 309,
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
        'cachedTotalScore' => 184,
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
        'cachedTotalScore' => 315,
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
        'cachedTotalScore' => 360,
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
        'cachedTotalScore' => 296,
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
        'cachedTotalScore' => 216,
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
        'cachedTotalScore' => 339,
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
        'cachedTotalScore' => 327,
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
        'lastName' => 'Mrazik',
        'club' => 'Chabenec',
        'teamName' => 'Chabenec',
        'cachedTotalScore' => 257,
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
        'cachedTotalScore' => 352,
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
        'cachedTotalScore' => 256,
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
        'cachedTotalScore' => 134,
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
        'cachedTotalScore' => 273,
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
        'firstName' => 'Ľuboš ml.',
        'lastName' => 'Kočtúch',
        'club' => 'Brtkovica',
        'teamName' => 'Brtkovica',
        'cachedTotalScore' => 321,
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
        'cachedTotalScore' => 242,
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
        'cachedTotalScore' => 201,
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
        'cachedTotalScore' => 220,
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
        'cachedTotalScore' => 254,
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
        'cachedTotalScore' => 246,
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
        'cachedTotalScore' => 285,
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
        'cachedTotalScore' => 390,
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
        'cachedTotalScore' => 315,
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
        'cachedTotalScore' => 290,
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
        'cachedTotalScore' => 301,
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
        'cachedTotalScore' => 339,
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
        'cachedTotalScore' => 393,
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
        'cachedTotalScore' => 239,
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
        'cachedTotalScore' => 293,
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
        'cachedTotalScore' => 355,
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
        'firstName' => 'Ľuboš st.',
        'lastName' => 'Kočtúch',
        'club' => 'Brtkovica',
        'teamName' => 'Brtkovica',
        'cachedTotalScore' => 299,
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
        'cachedTotalScore' => 286,
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
        'cachedTotalScore' => 317,
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
        'cachedTotalScore' => 191,
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
        'cachedTotalScore' => 227,
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
        'cachedTotalScore' => 380,
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
        'cachedTotalScore' => 191,
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
        'cachedTotalScore' => 196,
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
        'cachedTotalScore' => 369,
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
        'cachedTotalScore' => 246,
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
        'lastName' => 'Mrazik',
        'club' => 'Chabenec',
        'teamName' => 'Chabenec',
        'cachedTotalScore' => 324,
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
        'cachedTotalScore' => 221,
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
        'cachedTotalScore' => 367,
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
        'cachedTotalScore' => 229,
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
        'cachedTotalScore' => 350,
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
        'cachedTotalScore' => 339,
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
        'cachedTotalScore' => 317,
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
        'cachedTotalScore' => 381,
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
        'cachedTotalScore' => 302,
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
        'cachedTotalScore' => 216,
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
        'cachedTotalScore' => 269,
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
        'cachedTotalScore' => 324,
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
        'cachedTotalScore' => 367,
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
        'cachedTotalScore' => 355,
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
        'cachedTotalScore' => 337,
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
        'cachedTotalScore' => 299,
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
        'cachedTotalScore' => 341,
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

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TargetSnapshotFactory $targetSnapshotFactory,
    ) {
    }

    #[Override]
    public function build(): void
    {
        $competitionType = $this->getOrCreateM400CompetitionType();

        $competition = new Competition();
        $competition->setCompetitionType($competitionType);
        $competition->setName('Majstrovstvá okresu LM');
        $competition->setCompetitionStart(new DateTimeImmutable('2024-05-18 07:00:00'));
        $competition->setLocation('Strelnica Dovalovo');
        $competition->setOrganizer('OPK LM');
        $competition->setStatus(CompetitionStatus::Finished);
        $competition->setTargetConfigurationSnapshot($this->targetSnapshotFactory->createFromCompetitionType($competitionType));
        $competition->setTeamMemberCount(3);
        $competition->setShootersInRound(9);
        $competition->setMainCategoryName('Memoriál Antona Krištofa');

        $this->entityManager->persist($competition);

        $veteranCategory = $this->createCategory($competition, 'Veteráni');
        $seniorCategory = $this->createCategory($competition, 'Seniori');
        $womanCategory = $this->createCategory($competition, 'Ženy');
        $teams = [];

        foreach (self::COMPETITORS as $competitorData) {
            $shooter = $this->getOrCreateShooter($competitorData['firstName'], $competitorData['lastName'], $competitorData['club']);

            $competitor = new Competitor();
            $competitor->setCompetition($competition);
            $competitor->setShooter($shooter);
            $competitor->setStartNumber($competitorData['startNumber']);
            $competitor->setStatus(CompetitorStatus::Registered);
            $competitor->setCachedTotalScore($competitorData['cachedTotalScore']);
            $competitor->setCompetitionTeam($this->getOrCreateTeam($competition, $competitorData['teamName'], $teams));

            if ($competitorData['veteran']) {
                $competitor->addCategory($veteranCategory);
            } else {
                $competitor->addCategory($seniorCategory);
            }

            if ($competitorData['woman']) {
                $competitor->addCategory($womanCategory);
            }

            foreach ($competitorData['targetResults'] as $targetResultData) {
                $targetResult = new TargetResult();
                $targetResult->setCompetitor($competitor);
                $targetResult->setTargetName($targetResultData['targetName']);
                $targetResult->setHitBreakdown($targetResultData['hitBreakdown']);
                $competitor->addTargetResult($targetResult);
            }

            $competition->addCompetitor($competitor);
        }

        $this->entityManager->flush();
    }

    private function getOrCreateM400CompetitionType(): CompetitionType
    {
        $competitionType = $this->entityManager->getRepository(CompetitionType::class)
            ->findOneBy(['name' => 'M400']);

        if ($competitionType instanceof CompetitionType) {
            return $competitionType;
        }

        M400TypeStory::load();

        $competitionType = $this->entityManager->getRepository(CompetitionType::class)
            ->findOneBy(['name' => 'M400']);

        if (!$competitionType instanceof CompetitionType) {
            throw new RuntimeException('CompetitionType M400 was not created.');
        }

        return $competitionType;
    }

    private function createCategory(Competition $competition, string $name): CompetitionCategory
    {
        $category = new CompetitionCategory();
        $category->setCompetition($competition);
        $category->setName($name);

        $competition->addCategory($category);
        $this->entityManager->persist($category);

        return $category;
    }

    private function getOrCreateShooter(string $firstName, string $lastName, ?string $club): Shooter
    {
        $shooter = $this->entityManager->getRepository(Shooter::class)
            ->findOneBy([
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);

        if (!$shooter instanceof Shooter) {
            $shooter = new Shooter();
            $shooter->setFirstName($firstName);
            $shooter->setLastName($lastName);
            $this->entityManager->persist($shooter);
        }

        if ($club !== null) {
            $shooter->setClub($club);
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

        $team = new CompetitionTeam();
        $team->setCompetition($competition);
        $team->setName($teamName);

        $teams[$teamName] = $team;
        $this->entityManager->persist($team);

        return $team;
    }
}
