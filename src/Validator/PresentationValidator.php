<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Competition;
use App\Entity\CompetitionTeam;
use App\Entity\Competitor;
use App\Entity\Shooter;
use App\Form\Dto\PresentationDto;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class PresentationValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Presentation) {
            throw new UnexpectedTypeException($constraint, Presentation::class);
        }

        if (!$value instanceof PresentationDto) {
            throw new UnexpectedValueException($value, PresentationDto::class);
        }

        if ($value->shooter !== null) {
            $this->validateShooterAlreadyInCompetition($value->shooter, $value->competition, $constraint);
        }

        if ($value->shooter === null) {
            $this->validateShooterAlreadyExists($value, $constraint);
            $this->validateShooterFields($value, $constraint);
        }

        if ($value->competitionTeam === null && $value->teamName !== null) {
            $this->validateTeamAlreadyExists($value->teamName, $value->competition, $constraint);
        }
    }

    private function validateShooterFields(PresentationDto $value, Presentation $constraint): void
    {
        if ($value->firstName === null || trim($value->firstName) === '') {
            $this->context->buildViolation($constraint->missingValuesMessage)
                ->atPath('firstName')
                ->addViolation();
        }

        if ($value->lastName === null || trim($value->lastName) === '') {
            $this->context->buildViolation($constraint->missingValuesMessage)
                ->atPath('lastName')
                ->addViolation();
        }
    }

    private function validateTeamAlreadyExists(
        string $teamName,
        Competition $competition,
        Presentation $constraint,
    ): void {
        $exists = $this->entityManager->getRepository(CompetitionTeam::class)->findOneBy([
            'competition' => $competition,
            'name' => $teamName,
        ]);

        if ($exists === null) {
            return;
        }

        $this->context->buildViolation($constraint->teamAlreadyExistsMessage)
            ->atPath('teamName')
            ->setParameter('{{ teamName }}', $teamName)
            ->setParameter('{{ competition }}', $competition->getName())
            ->addViolation();
    }

    private function validateShooterAlreadyExists(PresentationDto $value, Presentation $constraint): void
    {
        $exists = $this->entityManager->getRepository(Shooter::class)->findOneBy([
            'firstName' => $value->firstName,
            'lastName' => $value->lastName,
        ]);

        if ($exists === null) {
            return;
        }

        $this->context->buildViolation($constraint->shooterAlreadyExistsMessage)
            ->atPath('lastName')
            ->setParameter('{{ firstName }}', $value->firstName)
            ->setParameter('{{ lastName }}', $value->lastName)
            ->addViolation();
    }

    private function validateShooterAlreadyInCompetition(
        Shooter $shooter,
        Competition $competition,
        Presentation $constraint,
    ): void {
        $exists = $this->entityManager->getRepository(Competitor::class)->findOneBy([
            'shooter' => $shooter,
            'competition' => $competition
        ]);

        if ($exists === null) {
            return;
        }

        $this->context->buildViolation($constraint->shooterAlreadyInCompetitionMessage)
            ->atPath('shooter')
            ->setParameter('{{ shooter }}', $shooter->getFullName())
            ->setParameter('{{ competition }}', $competition->getName())
            ->addViolation();
    }
}
