<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\TargetPointsSchema;
use App\Repository\TargetDefinitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TargetDefinitionRepository::class)]
#[ORM\Table(name: 'target_definitions')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['name'])]
class TargetDefinition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::JSON)]
    private TargetPointsSchema $pointsSchema;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPointsSchema(): TargetPointsSchema
    {
        return $this->pointsSchema;
    }

    public function setPointsSchema(TargetPointsSchema $pointsSchema): void
    {
        $this->pointsSchema = $pointsSchema;
    }
}
