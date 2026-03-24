<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompetitionTeamRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;

#[ORM\Entity(repositoryClass: CompetitionTeamRepository::class)]
#[ORM\Table(name: 'competition_team')]
#[ORM\UniqueConstraint(name: 'uniq_competition_team_name', columns: ['competition_id', 'name'])]
class CompetitionTeam implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
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
    #[ORM\OneToMany(
        targetEntity: Competitor::class,
        mappedBy: 'competitionTeam',
    )]
    private Collection $members;

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
    public function getMembers(): Collection
    {
        return $this->members;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
