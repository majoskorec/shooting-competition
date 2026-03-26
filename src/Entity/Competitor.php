<?php

declare(strict_types=1);

namespace App\Entity;

use App\Competition\Model\CompetitorStatus;
use App\Repository\CompetitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompetitorRepository::class)]
#[ORM\Table(name: 'competitor')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competition_id', 'shooter_id'])]
#[UniqueEntity(fields: ['competition', 'shooter'])]
class Competitor implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'competitors')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Competition $competition;

    #[ORM\ManyToOne(
        cascade: ['persist'],
        inversedBy: 'competitors',
    )]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Shooter $shooter;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CompetitionTeam $competitionTeam = null;

    #[ORM\Column(nullable: true)]
    private ?int $startNumber = null;

    /** @var Collection<int, CompetitionCategory> */
    #[ORM\ManyToMany(
        targetEntity: CompetitionCategory::class,
        mappedBy: 'competitors',
    )]
    private Collection $categories;

    #[Assert\NotBlank(allowNull: true)]
    #[ORM\Column(length: 128, nullable: true)]
    private ?string $sharedWeaponCode = null;

    #[ORM\Column(enumType: CompetitorStatus::class)]
    private CompetitorStatus $status;

    #[ORM\Column(nullable: true)]
    private ?int $cachedTotalScore = null;

    /** @var Collection<int, TargetResult> */
    #[ORM\OneToMany(
        targetEntity: TargetResult::class,
        mappedBy: 'competitor',
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $targetResults;

    public function __construct()
    {
        $this->targetResults = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getStatus(): CompetitorStatus
    {
        return $this->status;
    }

    public function setStatus(CompetitorStatus $status): void
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

    public function addTargetResult(TargetResult $targetResult): void
    {
        if (!$this->targetResults->contains($targetResult)) {
            $this->targetResults->add($targetResult);
            $targetResult->setCompetitor($this);
        }
    }

    public function removeTargetResult(TargetResult $targetResult): void
    {
        $this->targetResults->removeElement($targetResult);
    }

    /** @return Collection<int, CompetitionCategory> */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CompetitionCategory $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addCompetitor($this);
        }
    }

    public function removeCategory(CompetitionCategory $category): void
    {
        if ($this->categories->removeElement($category)) {
            $category->removeCompetitor($this);
        }
    }

    #[Override]
    public function __toString(): string
    {
        return $this->shooter->getFullName();
    }
}
