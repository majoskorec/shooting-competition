<?php

declare(strict_types=1);

namespace App\Repository;

use App\Competition\Results\Model\Category;
use App\Competition\Results\Model\CategoryType;
use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findForCompetitionAndCategory(Competition $competition, Category $category): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb = $qb->select(['c', 's', 't', 'r']);
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->join('c.targetResults', 'r');
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
        $qb = $qb->setParameter('competition', $competition);
        $qb = $qb->andWhere('c.startNumber is not null');

        return $qb->getQuery()->getResult();
    }
}
