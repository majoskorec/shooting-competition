<?php

declare(strict_types=1);

namespace App\Tests\Integration\Competition\Draw;

use App\Competition\Draw\DrawService;
use App\Competition\Draw\Exception\StartNumberAssignmentException;
use App\Competition\Model\CompetitionStatus;
use App\Entity\Competition;
use App\Entity\Competitor;
use App\Entity\Shooter;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

final class DrawServiceTest extends TestCase
{
    public function testAssignStartNumbersSeparatesSharedWeaponAcrossRounds(): void
    {
        $competition = new Competition();
        $competition->setShootersInRound(2);
        $competition->setStatus(CompetitionStatus::Presentation);

        $competitors = [
            $this->createCompetitor($competition, 'Adam', 'A', 'W1'),
            $this->createCompetitor($competition, 'Berta', 'B', 'W1'),
            $this->createCompetitor($competition, 'Cyril', 'C'),
            $this->createCompetitor($competition, 'Diana', 'D'),
        ];

        foreach ($competitors as $competitor) {
            $competition->addCompetitor($competitor);
        }

        $drawService = new DrawService(new Randomizer());
        $drawService($competition);
        foreach ($competitors as $competitor) {
            self::assertNotNull($competitor->getStartNumber());
        }

        self::assertNotSame(
            $this->roundFor($competitors[0]->getStartNumber(), $competition->getShootersInRound()),
            $this->roundFor($competitors[1]->getStartNumber(), $competition->getShootersInRound()),
        );
    }

    public function testAssignStartNumbersCanProduceDifferentStartNumbersAcrossRuns(): void
    {
        $competitionOne = $this->createCompetitionForRandomnessCheck();
        $competitionTwo = $this->createCompetitionForRandomnessCheck();

        $drawService = new DrawService(new Randomizer());

        $drawService($competitionOne);
        $drawService($competitionTwo);

        self::assertNotSame(
            $this->startNumbersByShooter($competitionOne),
            $this->startNumbersByShooter($competitionTwo),
        );
    }

    public function testAssignStartNumbersFailsWhenSharedWeaponCannotBeSeparated(): void
    {
        $competition = new Competition();
        $competition->setShootersInRound(2);
        $competition->setStatus(CompetitionStatus::Presentation);

        $competition->addCompetitor($this->createCompetitor($competition, 'Adam', 'A', 'W1'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Berta', 'B', 'W1'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Cyril', 'C', 'W1'));

        $drawService = new DrawService(new Randomizer());

        $this->expectException(StartNumberAssignmentException::class);
        $drawService($competition);
    }

    private function createCompetitor(
        Competition $competition,
        string $firstName,
        string $lastName,
        ?string $sharedWeaponCode = null,
    ): Competitor {
        $shooter = new Shooter();
        $shooter->setFirstName($firstName);
        $shooter->setLastName($lastName);

        $competitor = new Competitor();
        $competitor->setCompetition($competition);
        $competitor->setShooter($shooter);
        $competitor->setSharedWeaponCode($sharedWeaponCode);

        return $competitor;
    }

    private function roundFor(int $startNumber, int $shootersInRound): int
    {
        return (int) ceil($startNumber / $shootersInRound);
    }

    private function createCompetitionForRandomnessCheck(): Competition
    {
        $competition = new Competition();
        $competition->setShootersInRound(3);
        $competition->setStatus(CompetitionStatus::Presentation);

        $competition->addCompetitor($this->createCompetitor($competition, 'Adam', 'A', 'W1'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Berta', 'B', 'W1'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Cyril', 'C', 'W2'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Diana', 'D', 'W2'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Eva', 'E'));
        $competition->addCompetitor($this->createCompetitor($competition, 'Filip', 'F'));

        return $competition;
    }

    /**
     * @return array<string, int|null>
     */
    private function startNumbersByShooter(Competition $competition): array
    {
        $startNumbers = [];
        foreach ($competition->getCompetitors() as $competitor) {
            $startNumbers[$competitor->getShooter()->getFullName()] = $competitor->getStartNumber();
        }

        ksort($startNumbers);

        return $startNumbers;
    }
}
