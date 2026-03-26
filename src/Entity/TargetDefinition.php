<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TargetDefinitionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TargetDefinitionRepository::class)]
#[ORM\Table(name: 'target_definition')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['name'])]
class TargetDefinition implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(length: 255)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 32)]
    #[ORM\Column(length: 32)]
    private string $shortName;

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

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): void
    {
        $this->shortName = $shortName;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
