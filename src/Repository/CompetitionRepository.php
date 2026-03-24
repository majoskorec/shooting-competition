<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Competition;
use App\Model\Enum\CompetitionStatus;
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
    public function findActive(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->andWhere('c.status in (:activeStatuses)');
        $qb = $qb->setParameter('activeStatuses', [
            CompetitionStatus::Presentation,
            CompetitionStatus::InProgress,
            CompetitionStatus::ReadyForClosure,
            CompetitionStatus::Finalized,
        ]);

        return $qb->getQuery()->getResult();
    }
}
