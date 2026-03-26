<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompetitionTypeTargetRepository;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompetitionTypeTargetRepository::class)]
#[ORM\Table(name: 'competition_type_target')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competition_type_id', 'target_definition_id', 'display_order'])]
class CompetitionTypeTarget implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(
        inversedBy: 'targets',
    )]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'CASCADE',
    )]
    private CompetitionType $competitionType;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(
        nullable: false,
        onDelete: 'RESTRICT',
    )]
    private TargetDefinition $targetDefinition;

    #[ORM\Column]
    private int $displayOrder;

    #[Assert\Range(min: 1)]
    #[ORM\Column]
    private int $shotCount;

    #[Assert\Range(min: 1, max: 99)]
    #[ORM\Column]
    private int $tieBreakPriority;

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

    public function getTargetDefinition(): TargetDefinition
    {
        return $this->targetDefinition;
    }

    public function setTargetDefinition(TargetDefinition $targetDefinition): void
    {
        $this->targetDefinition = $targetDefinition;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): void
    {
        $this->displayOrder = $displayOrder;
    }

    public function getShotCount(): int
    {
        return $this->shotCount;
    }

    public function setShotCount(int $shotCount): void
    {
        $this->shotCount = $shotCount;
    }

    public function getTieBreakPriority(): int
    {
        return $this->tieBreakPriority;
    }

    public function setTieBreakPriority(int $tieBreakPriority): void
    {
        $this->tieBreakPriority = $tieBreakPriority;
    }

    #[Override]
    public function __toString()
    {
        return sprintf('%s - %d', $this->targetDefinition->getName(), $this->displayOrder);
    }
}
