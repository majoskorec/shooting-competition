<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\JuryEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: JuryEntryRepository::class)]
#[ORM\Table(name: 'jury_entry')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competitor_id', 'category_id'])]
#[UniqueEntity(fields: ['competitor', 'category'])]
class JuryEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'juryEntries')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Competitor $competitor;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CompetitionCategory $category = null;

    #[ORM\Column]
    private int $points;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetitor(): Competitor
    {
        return $this->competitor;
    }

    public function setCompetitor(Competitor $competitor): void
    {
        $this->competitor = $competitor;
    }

    public function getCategory(): ?CompetitionCategory
    {
        return $this->category;
    }

    public function setCategory(?CompetitionCategory $category): void
    {
        $this->category = $category;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
