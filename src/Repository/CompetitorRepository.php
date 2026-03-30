<?php

declare(strict_types=1);

namespace App\Repository;

use App\Competition\Model\CompetitorStatus;
use App\Competition\Results\Model\Category;
use App\Competition\Results\Model\CategoryType;
use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competitor>
 */
final class CompetitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competitor::class);
    }

    /**
     * @return array<Competitor>
     */
    public function findForStartingList(Competition $competition): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->select(['c', 's', 't', 'cat']);
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->leftJoin('c.competitionTeam', 't');
        $qb = $qb->leftJoin('c.categories', 'cat');
        $qb = $qb->andWhere('c.competition = :competition');
        $qb = $qb->setParameter('competition', $competition->getId(), Types::INTEGER);
        $qb = $qb->addOrderBy('c.startNumber', 'ASC');
        /** @var array<Competitor> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @return array<Competitor>
     */
    public function findForPresentation(Competition $competition): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->select(['c', 's', 't', 'cat']);
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->leftJoin('c.competitionTeam', 't');
        $qb = $qb->leftJoin('c.categories', 'cat');
        $qb = $qb->andWhere('c.competition = :competition');
        $qb = $qb->setParameter('competition', $competition->getId(), Types::INTEGER);
        $qb = $qb->andWhere('c.status in (:statuses)');
        $qb = $qb->setParameter('statuses', [
            CompetitorStatus::Registered,
        ]);
        $qb = $qb->addOrderBy('s.lastName', 'ASC');
        $qb = $qb->addOrderBy('s.firstName', 'ASC');
        /** @var array<Competitor> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @return array<int>
     */
    public function findRegisteredShooterIdsForCompetition(Competition $competition): array
    {
        /** @var array<array{shooterId: int}> $result */
        $result = $this->createQueryBuilder('c')
            ->select('IDENTITY(c.shooter) AS shooterId')
            ->andWhere('c.competition = :competition')
            ->setParameter('competition', $competition->getId(), Types::INTEGER)
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'shooterId');
    }

    /**
     * @return array<Competitor>
     */
    public function findForCompetitionAndCategory(Competition $competition, Category $category): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->select(['c', 's', 't', 'r', 'j']);
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->join('c.targetResults', 'r');
        $qb = $qb->leftJoin('c.juryEntries', 'j');
        if ($category->categoryType === CategoryType::Teams) {
            $qb = $qb->join('c.competitionTeam', 't');
        }
        if ($category->categoryType !== CategoryType::Teams) {
            $qb = $qb->leftJoin('c.competitionTeam', 't');
        }
        if ($category->categoryType === CategoryType::Custom) {
            $qb = $qb->addSelect('cat');
            $qb = $qb->join('c.categories', 'cat');
            $qb = $qb->andWhere('cat.name = :categoryName');
            $qb = $qb->setParameter('categoryName', $category->title);
        }
        $qb = $qb->andWhere('c.competition = :competition');
        $qb = $qb->setParameter('competition', $competition->getId(), Types::INTEGER);
        $qb = $qb->andWhere('c.startNumber is not null');
        $qb = $qb->andWhere('c.status = :status');
        $qb = $qb->setParameter('status', CompetitorStatus::Registered);
        /** @var array<Competitor> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @return array<Competitor>
     */
    public function findForInput(Competition $competition): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->select(['c', 's', 't', 'cat', 'r']);
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->leftJoin('c.competitionTeam', 't');
        $qb = $qb->leftJoin('c.categories', 'cat');
        $qb = $qb->leftJoin('c.targetResults', 'r');
        $qb = $qb->andWhere('c.competition = :competition');
        $qb = $qb->setParameter('competition', $competition->getId(), Types::INTEGER);
        $qb = $qb->addOrderBy('c.startNumber', 'ASC');
        /** @var array<Competitor> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
