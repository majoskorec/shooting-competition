<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompetitionCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;

#[ORM\Entity(repositoryClass: CompetitionCategoryRepository::class)]
#[ORM\Table(name: 'competition_category')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competition_id', 'name'])]
class CompetitionCategory implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(
        targetEntity: Competition::class,
        inversedBy: 'categories',
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE',
    )]
    private Competition $competition;

    #[ORM\Column(
        length: 255,
    )]
    private string $name;

    /** @var Collection<int, Competitor> */
    #[ORM\ManyToMany(
        targetEntity: Competitor::class,
        inversedBy: 'categories',
    )]
    private Collection $competitors;

    public function __construct()
    {
        $this->competitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetition(): Competition
    {
        return $this->competition;
    }

    public function setCompetition(Competition $competition): void
    {
        $this->competition = $competition;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /** @return Collection<int, Competitor> */
    public function getCompetitors(): Collection
    {
        return $this->competitors;
    }

    public function addCompetitor(Competitor $competitor): void
    {
        if (!$this->competitors->contains($competitor)) {
            $this->competitors->add($competitor);
        }
    }

    public function removeCompetitor(Competitor $competitor): void
    {
        $this->competitors->removeElement($competitor);
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
