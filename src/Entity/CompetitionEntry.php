<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Enum\CompetitionEntryStatus;
use App\Repository\CompetitionEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetitionEntryRepository::class)]
#[ORM\Table(name: 'competition_entries')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competition_id', 'shooter_id'])]
class CompetitionEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Competition $competition;

    #[ORM\ManyToOne(inversedBy: 'competitionEntries')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Shooter $shooter;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CompetitionTeam $competitionTeam = null;

    #[ORM\Column(nullable: true)]
    private ?int $startNumber = null;

//    #[ORM\Column(length: 128, nullable: true)]
//    private ?string $category = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $sharedWeaponCode = null;

    #[ORM\Column(enumType: CompetitionEntryStatus::class)]
    private CompetitionEntryStatus $status;

    #[ORM\Column(nullable: true)]
    private ?int $cachedTotalScore = null;

    /** @var Collection<int, TargetResult> */
    #[ORM\OneToMany(
        targetEntity: TargetResult::class,
        mappedBy: 'competitionEntry',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $targetResults;

    public function __construct()
    {
        $this->targetResults = new ArrayCollection();
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

    public function getShooter(): Shooter
    {
        return $this->shooter;
    }

    public function setShooter(Shooter $shooter): void
    {
        $this->shooter = $shooter;
    }

    public function getCompetitionTeam(): ?CompetitionTeam
    {
        return $this->competitionTeam;
    }

    public function setCompetitionTeam(?CompetitionTeam $competitionTeam): void
    {
        $this->competitionTeam = $competitionTeam;
    }

    public function getStartNumber(): ?int
    {
        return $this->startNumber;
    }

    public function setStartNumber(?int $startNumber): void
    {
        $this->startNumber = $startNumber;
    }

    public function getSharedWeaponCode(): ?string
    {
        return $this->sharedWeaponCode;
    }

    public function setSharedWeaponCode(?string $sharedWeaponCode): void
    {
        $this->sharedWeaponCode = $sharedWeaponCode;
    }

    public function getStatus(): CompetitionEntryStatus
    {
        return $this->status;
    }

    public function setStatus(CompetitionEntryStatus $status): void
    {
        $this->status = $status;
    }

    public function getCachedTotalScore(): ?int
    {
        return $this->cachedTotalScore;
    }

    public function setCachedTotalScore(?int $cachedTotalScore): void
    {
        $this->cachedTotalScore = $cachedTotalScore;
    }

    /** @return Collection<int, TargetResult> */
    public function getTargetResults(): Collection
    {
        return $this->targetResults;
    }

    public function addTargetResult(TargetResult $targetResult): self
    {
        if (!$this->targetResults->contains($targetResult)) {
            $this->targetResults->add($targetResult);
            $targetResult->setCompetitionEntry($this);
        }

        return $this;
    }

    public function removeTargetResult(TargetResult $targetResult): void
    {
        $this->targetResults->removeElement($targetResult);
    }
}
