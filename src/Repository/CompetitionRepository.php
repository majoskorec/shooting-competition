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
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->andWhere('c.status in (:activeStatuses)');
        $qb = $qb->setParameter('activeStatuses', [
            CompetitionStatus::Presentation,
            CompetitionStatus::InProgress,
            CompetitionStatus::ReadyForClosure,
            CompetitionStatus::Finished,
        ]);
        $qb = $qb->addOrderBy('c.competitionStart', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array<Competition>
     */
    public function findActive(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->andWhere('c.status in (:activeStatuses)');
        $qb = $qb->setParameter('activeStatuses', [
            CompetitionStatus::Presentation,
            CompetitionStatus::InProgress,
            CompetitionStatus::ReadyForClosure,
        ]);
        $qb = $qb->addOrderBy('c.competitionStart', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
