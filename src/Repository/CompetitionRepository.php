<?php

declare(strict_types=1);

namespace App\Repository;

use App\Competition\Model\CompetitionStatus;
use App\Entity\Competition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competition>
 */
final class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    /**
     * @return array<Competition>
     */
    public function findPublic(): array
    {
        return $this->findWithStatuses([
            CompetitionStatus::Presentation,
            CompetitionStatus::InProgress,
            CompetitionStatus::ReadyForClosure,
            CompetitionStatus::Finished,
        ]);
    }

    /**
     * @return array<Competition>
     */
    public function findActive(): array
    {
        return $this->findWithStatuses([
            CompetitionStatus::Presentation,
            CompetitionStatus::InProgress,
            CompetitionStatus::ReadyForClosure,
        ]);
    }

    /**
     * @param array<CompetitionStatus> $statuses
     * @return array<Competition>
     */
    private function findWithStatuses(array $statuses): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->andWhere('c.status in (:statuses)');
        $qb = $qb->setParameter('statuses', $statuses);
        $qb = $qb->addOrderBy('c.competitionStart', 'DESC');
        /** @var array<Competition> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
