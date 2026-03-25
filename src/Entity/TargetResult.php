<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TargetResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TargetResultRepository::class)]
#[ORM\Table(name: 'target_result')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['competitor_id', 'target_name'])]
#[ORM\HasLifecycleCallbacks]
class TargetResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'targetResults')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Competitor $competitor;

    #[ORM\Column(length: 255)]
    private string $targetName;

    /** @var array<int, int> */
    #[ORM\Column(type: Types::JSON)]
    private array $hitBreakdown = [];

    #[ORM\Column(nullable: true)]
    private ?int $subtotal = null;

    #[ORM\Column]
    private bool $consistent;

    /** @var array<int, string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $validationIssues = null;

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

    public function getTargetName(): string
    {
        return $this->targetName;
    }

    public function setTargetName(string $targetName): void
    {
        $this->targetName = $targetName;
    }

    /** @return array<int, int> */
    public function getHitBreakdown(): array
    {
        return $this->hitBreakdown;
    }

    /** @param array<int, int> $hitBreakdown */
    public function setHitBreakdown(array $hitBreakdown): void
    {
        $this->hitBreakdown = $hitBreakdown;
    }

    public function getSubtotal(): ?int
    {
        return $this->subtotal;
    }

    public function setSubtotal(?int $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function isConsistent(): bool
    {
        return $this->consistent;
    }

    public function setConsistent(bool $consistent): void
    {
        $this->consistent = $consistent;
    }

    /** @return array<int, string>|null */
    public function getValidationIssues(): ?array
    {
        return $this->validationIssues;
    }

    /** @param array<int, string>|null $validationIssues */
    public function setValidationIssues(?array $validationIssues): void
    {
        $this->validationIssues = $validationIssues;
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $subTotal = 0;
        foreach ($this->hitBreakdown as $points => $hits) {
            $subTotal += $points * $hits;
        }
        $this->subtotal = $subTotal;
    }
}
