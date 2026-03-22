<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CompetitionTypeTarget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetitionTypeTarget>
 */
class CompetitionTypeTargetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetitionTypeTarget::class);
    }
}
