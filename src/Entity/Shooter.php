<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ShooterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Stringable;

#[ORM\Entity(repositoryClass: ShooterRepository::class)]
#[ORM\Table(name: 'shooter')]
#[ORM\UniqueConstraint(name: 'uniq_idx', columns: ['last_name', 'first_name'])]
class Shooter implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $club = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /** @var Collection<int, Competitor> */
    #[ORM\OneToMany(targetEntity: Competitor::class, mappedBy: 'shooter')]
    private Collection $competitors;

    public function __construct()
    {
        $this->competitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(?string $club): void
    {
        $this->club = $club;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /** @return Collection<int, Competitor> */
    public function getCompetitors(): Collection
    {
        return $this->competitors;
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->firstName, $this->lastName));
    }

    #[Override]
    public function __toString(): string
    {
        return $this->getFullName();
    }
}
