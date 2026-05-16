<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Competition\Model\CompetitorStatus;
use App\Competition\Model\Exception\InvalidFieldValueException;
use App\Controller\Admin\Competition\PresentationController;
use App\Entity\Competition;
use App\Entity\CompetitionTeam;
use App\Entity\Competitor;
use App\Entity\Shooter;
use App\Entity\ShooterGender;
use App\Form\Dto\PresentationDto;
use App\Form\Type\PresentationDtoType;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Presentation extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Competition $competition;

    /**
     * @var array<string, array<Competitor>>|null
     */
    private ?array $sharedWeapons = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[LiveAction]
    public function save(): Response
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        $presentationDto = $this->getForm()->getData();
        assert($presentationDto instanceof PresentationDto);
        $shooter = $this->persistShooter($presentationDto);
        $competitionTeam = $this->persistTeam($presentationDto);

        $competitor = new Competitor();
        $competitor->setCompetition($this->competition);
        $competitor->setCompetitionTeam($competitionTeam);
        $competitor->setShooter($shooter);
        $competitor->setSharedWeaponCode($presentationDto->sharedWeaponCode);
        $competitor->setStatus(CompetitorStatus::Registered);
        foreach ($presentationDto->categories as $category) {
            $competitor->addCategory($category);
        }
        $this->entityManager->persist($competitor);

        $this->entityManager->flush();

        $this->addFlash('success', 'Účastník bol zaregistrovaný');

        return $this->redirectToRoute(PresentationController::ROUTE_NAME, [
            'entityId' => $this->competition->getId(),
        ]);
    }

    /**
     * @return array<string, array<Competitor>>
     */
    public function getSharedWeapons(): array
    {
        if ($this->sharedWeapons !== null) {
            return $this->sharedWeapons;
        }

        $result = [];
        foreach ($this->competition->getCompetitors() as $competitor) {
            $code = $competitor->getSharedWeaponCode();
            if ($code === null) {
                continue;
            }

            $result[$code][] = $competitor;
        }
        krsort($result);
        $this->sharedWeapons = $result;

        return $this->sharedWeapons;
    }

    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function getNewWeaponCode(): string
    {
        $lastWeaponCode = array_key_first($this->getSharedWeapons());
        if ($lastWeaponCode === null) {
            return 'a';
        }

        $lastChar = substr($lastWeaponCode, -1);
        $prefix = substr($lastWeaponCode, 0, -1);
        if ($lastChar >= 'a' && $lastChar <= 'y') {
            $ord = ord($lastChar);
            assert($ord >= 97 && $ord <= 121);

            return $prefix . chr($ord + 1);
        }

        if ($lastChar === 'z') {
            return $lastWeaponCode . 'a';
        }

        if ($lastChar >= '1' && $lastChar <= '8') {
            return $prefix . ((string) ((int) $lastChar + 1));
        }

        if ($lastChar === '9') {
            return $lastWeaponCode . '0';
        }

        return $lastWeaponCode . 'a';
    }

    /**
     * @psalm-return FormInterface
     * @phpstan-return FormInterface<PresentationDto>
     */
    #[Override]
    protected function instantiateForm(): FormInterface
    {
        $this->fetchShooterData();
        $firstName = $this->formValues['firstName'] ?? null;
        $lastName = $this->formValues['lastName'] ?? null;
        $birthYear = $this->normalizeBirthYear($this->formValues['birthYear'] ?? null);
        /** @var array<string> $categoryIds */
        $categoryIds = $this->formValues['categories'] ?? [];
        $gender = $this->normalizeGender($this->formValues['gender'] ?? null);
        $this->fetchTeamData();
        $teamName = $this->formValues['teamName'] ?? null;

        return $this->createForm(PresentationDtoType::class, new PresentationDto($this->competition), [
            'birth_year' => $birthYear,
            'category_ids' => $categoryIds,
            'competition' => $this->competition,
            'first_name_filter' => $firstName,
            'gender' => $gender,
            'last_name_filter' => $lastName,
            'team_name_filter' => $teamName,
        ]);
    }

    private function persistTeam(PresentationDto $presentationDto): ?CompetitionTeam
    {
        $competitionTeam = $presentationDto->competitionTeam;
        if ($competitionTeam !== null) {
            return $competitionTeam;
        }

        if ($presentationDto->teamName === null) {
            return null;
        }

        $competitionTeam = new CompetitionTeam();
        $competitionTeam->setCompetition($this->competition);
        $competitionTeam->setName($presentationDto->teamName);
        $this->entityManager->persist($competitionTeam);

        return $competitionTeam;
    }

    private function persistShooter(PresentationDto $presentationDto): Shooter
    {
        $shooter = $presentationDto->shooter;
        if ($shooter === null) {
            $shooter = new Shooter();
            $shooter->setFirstName(
                $presentationDto->firstName
                    ?? throw InvalidFieldValueException::create($presentationDto, 'firstName'),
            );
            $shooter->setLastName(
                $presentationDto->lastName
                    ?? throw InvalidFieldValueException::create($presentationDto, 'lastName'),
            );

            $this->entityManager->persist($shooter);
        }

        $shooter->setClub($presentationDto->club);
        $shooter->setBirthYear(
            $presentationDto->birthYear
                ?? throw InvalidFieldValueException::create($presentationDto, 'birthYear'),
        );
        $shooter->setGender(
            $presentationDto->gender
                ?? throw InvalidFieldValueException::create($presentationDto, 'gender'),
        );
        $shooter->setEmail($presentationDto->email);

        return $shooter;
    }

    private function fetchTeamData(): void
    {
        $competitionTeam = $this->formValues['competitionTeam'] ?? '';
        assert(is_string($competitionTeam));
        if (trim($competitionTeam) === '') {
            return;
        }

        $teamEntity = $this->entityManager->getRepository(CompetitionTeam::class)->find($competitionTeam);
        if ($teamEntity === null) {
            return;
        }

        $this->formValues['teamName'] = $teamEntity->getName();
    }

    private function fetchShooterData(): ?Shooter
    {
        $shooter = $this->formValues['shooter'] ?? '';
        assert(is_string($shooter));
        if (trim($shooter) === '') {
            return null;
        }

        $shooterEntity = $this->entityManager->getRepository(Shooter::class)->find($shooter);
        if ($shooterEntity === null) {
            return null;
        }

        $this->formValues['firstName'] = $shooterEntity->getFirstName();
        $this->formValues['lastName'] = $shooterEntity->getLastName();
        $this->formValues['email'] = $shooterEntity->getEmail();
        $this->formValues['club'] = $shooterEntity->getClub();
        $this->formValues['birthYear'] = $shooterEntity->getBirthYear();
        $this->formValues['gender'] = $shooterEntity->getGender()->value;

        return $shooterEntity;
    }

    private function normalizeGender(mixed $gender): ?ShooterGender
    {
        if ($gender instanceof ShooterGender) {
            return $gender;
        }

        if (!is_string($gender) || trim($gender) === '') {
            return null;
        }

        return ShooterGender::tryFrom($gender);
    }

    private function normalizeBirthYear(mixed $birthYear): ?int
    {
        if (is_int($birthYear)) {
            return $birthYear;
        }

        if (!is_string($birthYear) || trim($birthYear) === '') {
            return null;
        }

        if (!ctype_digit($birthYear)) {
            return null;
        }

        return (int) $birthYear;
    }
}
