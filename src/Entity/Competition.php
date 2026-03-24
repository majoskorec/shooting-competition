<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Enum\CompetitionStatus;
use App\Model\TargetSnapshot;
use App\Repository\CompetitionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompetitionRepository::class)]
#[ORM\Table(name: 'competition')]
class Competition implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'RESTRICT',
    )]
    private CompetitionType $competitionType;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $competitionStart;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
    )]
    private ?string $description = null;

    #[ORM\Column(
        length: 255,
        nullable: true,
    )]
    private ?string $location = null;

    #[ORM\Column(
        length: 255,
        nullable: true,
    )]
    private ?string $organizer = null;

    #[ORM\Column(enumType: CompetitionStatus::class)]
    private CompetitionStatus $status;

    /** @var array<TargetSnapshot> */
    #[ORM\Column(type: Types::JSON)]
    private array $targetConfigurationSnapshot = [];

    /** @var Collection<int, Competitor> */
    #[ORM\OneToMany(
        targetEntity: Competitor::class,
        mappedBy: 'competition',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $competitors;

    #[Assert\AtLeastOneOf([
        new Assert\EqualTo(0),
        new Assert\GreaterThanOrEqual(2),
    ])]
    #[ORM\Column]
    private int $teamMemberCount;

    public function __construct()
    {
        $this->competitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetitionType(): CompetitionType
    {
        return $this->competitionType;
    }

    public function setCompetitionType(CompetitionType $competitionType): void
    {
        $this->competitionType = $competitionType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCompetitionStart(): DateTimeImmutable
    {
        return $this->competitionStart;
    }

    public function setCompetitionStart(DateTimeImmutable $competitionStart): void
    {
        $this->competitionStart = $competitionStart;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getOrganizer(): ?string
    {
        return $this->organizer;
    }

    public function setOrganizer(?string $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getStatus(): CompetitionStatus
    {
        return $this->status;
    }

    public function setStatus(CompetitionStatus $status): void
    {
        $this->status = $status;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return array<TargetSnapshot> */
    public function getTargetConfigurationSnapshot(): array
    {
        return $this->targetConfigurationSnapshot;
    }

    /** @param array<TargetSnapshot> $targetConfigurationSnapshot */
    public function setTargetConfigurationSnapshot(array $targetConfigurationSnapshot): void
    {
        $this->targetConfigurationSnapshot = $targetConfigurationSnapshot;
    }

    /** @return Collection<int, Competitor> */
    public function getCompetitors(): Collection
    {
        return $this->competitors;
    }

    public function addCompetitor(Competitor $competitor): self
    {
        if (!$this->competitors->contains($competitor)) {
            $this->competitors->add($competitor);
            $competitor->setCompetition($this);
        }

        return $this;
    }

    public function removeCompetitor(Competitor $competitor): void
    {
        $this->competitors->removeElement($competitor);
    }

    public function getTeamMemberCount(): int
    {
        return $this->teamMemberCount;
    }

    public function setTeamMemberCount(int $teamMemberCount): void
    {
        $this->teamMemberCount = $teamMemberCount;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->name;
    }
}
