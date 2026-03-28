<?php

declare(strict_types=1);

namespace App\Competition\Input;

use App\Competition\Input\Model\Input;
use App\Competition\Input\Model\InputCompetitor;
use App\Competition\Input\Model\InputTarget;
use App\Competition\Target\Model\TargetSnapshot;
use App\Entity\Competition;
use App\Entity\Competitor;
use App\Entity\TargetResult;
use Doctrine\ORM\EntityManagerInterface;

final class InputFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createInput(Competition $competition): Input
    {
        $competitors = $this->fetchCompetitors($competition);
        $inputCompetitors = [];
        foreach ($competitors as $competitor) {
            $inputCompetitors[$competitor->getStartNumber() ?? -1] = $this->createInputCompetitor(
                competitor: $competitor,
                competition: $competition,
            );
        }

        $this->entityManager->flush();

        return new Input(
            competition: $competition,
            inputCompetitors: $inputCompetitors,
        );
    }

    private function createInputCompetitor(Competitor $competitor, Competition $competition): InputCompetitor
    {
        $inputTargets = [];
        foreach ($competition->getTargetConfigurationSnapshotOrdered() as $targetSnapshot) {
            $inputTargets[$targetSnapshot->name] = $this->createInputTarget(
                targetSnapshot: $targetSnapshot,
                competitor: $competitor,
            );
        }

        return new InputCompetitor(
            competitor: $competitor,
            inputTargets: $inputTargets,
        );
    }

    private function createInputTarget(TargetSnapshot $targetSnapshot, Competitor $competitor): InputTarget
    {
        return InputTarget::create(
            targetSnapshot: $targetSnapshot,
            targetResult: $this->getTargetResult($targetSnapshot, $competitor),
            competitorStartNumber: $competitor->getStartNumber(),
            targetIndex: $targetSnapshot->displayOrder,
            competitionId: $competitor->getCompetition()->getId(),
        );
    }

    private function getTargetResult(TargetSnapshot $targetSnapshot, Competitor $competitor): TargetResult
    {
        foreach ($competitor->getTargetResults()->toArray() as $targetResult) {
            if ($targetResult->getTargetName() === $targetSnapshot->name) {
                return $targetResult;
            }
        }

        return $this->createTargetResult(
            targetSnapshot: $targetSnapshot,
            competitor: $competitor,
        );
    }

    private function createTargetResult(TargetSnapshot $targetSnapshot, Competitor $competitor): TargetResult
    {
        $entity = new TargetResult();
        $entity->setCompetitor($competitor);
        $entity->setTargetName($targetSnapshot->name);
        $entity->setHitBreakdown(array_fill_keys($targetSnapshot->pointsSchema, 0));
        $this->entityManager->persist($entity);

        return $entity;
    }

    /**
     * @return array<Competitor>
     */
    private function fetchCompetitors(Competition $competition): array
    {
        return $this->entityManager->getRepository(Competitor::class)
            ->createQueryBuilder('c')
            ->select(['c', 's', 't', 'cat', 'r'])
            ->join('c.shooter', 's')
            ->leftJoin('c.competitionTeam', 't')
            ->leftJoin('c.categories', 'cat')
            ->leftJoin('c.targetResults', 'r')
            ->andWhere('c.competition = :competition')
            ->setParameter('competition', $competition)
            ->addOrderBy('c.startNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
