<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompetitionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;

#[ORM\Entity(repositoryClass: CompetitionTypeRepository::class)]
#[ORM\Table(name: 'competition_type')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['name'])]
class CompetitionType implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
    )]
    private ?string $description = null;

    /** @var Collection<int, CompetitionTypeTarget> */
    #[ORM\OneToMany(
        targetEntity: CompetitionTypeTarget::class,
        mappedBy: 'competitionType',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    #[ORM\OrderBy(['displayOrder' => 'ASC'])]
    private Collection $targets;

    public function __construct()
    {
        $this->targets = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return Collection<int, CompetitionTypeTarget> */
    public function getTargets(): Collection
    {
        return $this->targets;
    }

    public function addTarget(CompetitionTypeTarget $target): self
    {
        if (!$this->targets->contains($target)) {
            $this->targets->add($target);
            $target->setCompetitionType($this);
        }

        return $this;
    }

    public function removeTarget(CompetitionTypeTarget $target): void
    {
        $this->targets->removeElement($target);
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
