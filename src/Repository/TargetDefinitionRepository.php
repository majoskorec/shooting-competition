<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TargetDefinition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TargetDefinition>
 */
final class TargetDefinitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TargetDefinition::class);
    }
}
