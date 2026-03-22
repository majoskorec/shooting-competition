<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TargetDefinitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TargetDefinitionRepository::class)]
#[ORM\Table(name: 'target_definition')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['name'])]
class TargetDefinition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    /** @var array<int, int> */
    #[ORM\Column(type: Types::JSON)]
    private array $pointsSchema;

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

    /** @return array<int, int> */
    public function getPointsSchema(): array
    {
        return $this->pointsSchema;
    }

    /** @param array<int, int> $pointsSchema */
    public function setPointsSchema(array $pointsSchema): void
    {
        $this->pointsSchema = $pointsSchema;
    }
}
