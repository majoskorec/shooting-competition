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

        $this->validateShooterAlreadyInCompetition($value->shooter, $value->competition, $constraint);
        $this->validateShooterAlreadyExists($value, $constraint);
        $this->validateShooterFields($value, $constraint);

        if ($value->competitionTeam === null && $value->teamName !== null) {
            $this->validateTeamAlreadyExists($value->teamName, $value->competition, $constraint);
        }

        if ($value->competitionTeam === null) {
            return;
        }

        $this->validateCompetitionTeamHasCapacity($value->competitionTeam, $value->competition, $constraint);
    }

    private function validateShooterFields(PresentationDto $value, Presentation $constraint): void
    {
        if ($value->shooter !== null) {
            return;
        }

        $this->validateRequiredStringField($value->firstName, 'firstName', $constraint);
        $this->validateRequiredStringField($value->lastName, 'lastName', $constraint);
        $this->validateRequiredBirthYear($value, $constraint);
        $this->validateRequiredGender($value, $constraint);
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
        if ($value->shooter !== null) {
            return;
        }

        $exists = $this->entityManager->getRepository(Shooter::class)->findOneBy([
            'birthYear' => $value->birthYear,
            'firstName' => $value->firstName,
            'gender' => $value->gender,
            'lastName' => $value->lastName,
        ]);

        if ($exists === null) {
            return;
        }

        $this->context->buildViolation($constraint->shooterAlreadyExistsMessage)
            ->atPath('lastName')
            ->setParameter('{{ firstName }}', $value->firstName ?? '')
            ->setParameter('{{ lastName }}', $value->lastName ?? '')
            ->addViolation();
    }

    private function validateShooterAlreadyInCompetition(
        ?Shooter $shooter,
        Competition $competition,
        Presentation $constraint,
    ): void {
        if ($shooter === null) {
            return;
        }

        $exists = $this->entityManager->getRepository(Competitor::class)->findOneBy([
            'competition' => $competition,
            'shooter' => $shooter,
        ]);

        if ($exists === null) {
            return;
        }

        $this->context->buildViolation($constraint->shooterAlreadyInCompetitionMessage)
            ->atPath('shooter')
            ->setParameter('{{ shooter }}', $shooter->getFullName())
            ->addViolation();
    }

    private function validateCompetitionTeamHasCapacity(
        CompetitionTeam $competitionTeam,
        Competition $competition,
        Presentation $constraint,
    ): void {
        if ($competition->getTeamMemberCount() < 2) {
            $this->context->buildViolation($constraint->competitionTeamIsDisabled)
                ->atPath('competitionTeam')
                ->addViolation();

            return;
        }

        if ($competitionTeam->getMembers()->count() < $competition->getTeamMemberCount()) {
            return;
        }

        $this->context->buildViolation($constraint->competitionTeamIsFullMessage)
            ->atPath('competitionTeam')
            ->setParameter('{{ teamName }}', $competitionTeam->getName())
            ->addViolation();
    }

    private function validateRequiredStringField(
        ?string $value,
        string $path,
        Presentation $constraint,
    ): void {
        if ($value !== null && trim($value) !== '') {
            return;
        }

        $this->context->buildViolation($constraint->missingValuesMessage)
            ->atPath($path)
            ->addViolation();
    }

    private function validateRequiredBirthYear(PresentationDto $value, Presentation $constraint): void
    {
        if ($value->birthYear !== null) {
            return;
        }

        $this->context->buildViolation($constraint->missingValuesMessage)
            ->atPath('birthYear')
            ->addViolation();
    }

    private function validateRequiredGender(PresentationDto $value, Presentation $constraint): void
    {
        if ($value->gender !== null) {
            return;
        }

        $this->context->buildViolation($constraint->missingValuesMessage)
            ->atPath('gender')
            ->addViolation();
    }
}
