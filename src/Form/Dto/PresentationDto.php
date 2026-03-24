<?php

declare(strict_types=1);

namespace App\Form\Dto;

use App\Entity\Competition;
use App\Entity\CompetitionTeam;
use App\Entity\Shooter;
use App\Validator\Presentation;
use Symfony\Component\Validator\Constraints as Assert;

#[Presentation]
final class PresentationDto
{
    public function __construct(
        public Competition $competition,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $club = null,
        #[Assert\Email]
        public ?string $email = null,
        public ?Shooter $shooter = null,
        public ?string $sharedWeaponCode = null,
        public ?string $teamName = null,
        public ?CompetitionTeam $competitionTeam = null,
    ) {
    }
}
