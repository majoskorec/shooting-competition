<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Controller\Admin\PresentationController;
use App\Entity\Competition;
use App\Entity\CompetitionTeam;
use App\Entity\Competitor;
use App\Entity\Shooter;
use App\Form\Dto\PresentationDto;
use App\Form\Type\PresentationDtoType;
use App\Model\Enum\CompetitorStatus;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGeneratorInterface;
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

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGeneratorInterface $adminUrlGenerator,
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
        $this->entityManager->persist($competitor);

        $this->entityManager->flush();

        $this->addFlash('success', 'Účastník bol zaregistrovaný');

        $url = $this->adminUrlGenerator->setRoute(PresentationController::ROUTE_NAME, [
            'id' => $this->competition->getId(),
        ])->generateUrl();

        return $this->redirect($url);
    }

    #[Override]
    protected function instantiateForm(): FormInterface
    {
        $this->fetchShooterData();
        $firstName = $this->formValues['firstName'] ?? null;
        $lastName = $this->formValues['lastName'] ?? null;
        $this->fetchTeamData();
        $teamName = $this->formValues['teamName'] ?? null;

        return $this->createForm(PresentationDtoType::class, new PresentationDto($this->competition), [
            'first_name_filter' => $firstName,
            'last_name_filter' => $lastName,
            'team_name_filter' => $teamName,
            'competition' => $this->competition,
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

        // @todo najprv selectnut ci existuje?
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
            $shooter->setFirstName($presentationDto->firstName);
            $shooter->setLastName($presentationDto->lastName);

            $this->entityManager->persist($shooter);
        }

        $shooter->setClub($presentationDto->club);
        $shooter->setEmail($presentationDto->email);

        return $shooter;
    }

    private function fetchTeamData(): void
    {
        $competitionTeam = $this->formValues['competitionTeam'] ?? null;
        if (trim($competitionTeam ?? '') === '') {
            return;
        }

        $teamEntity = $this->entityManager->getRepository(CompetitionTeam::class)->find($competitionTeam);
        if ($teamEntity === null) {
            return;
        }

        $this->formValues['teamName'] = $teamEntity->getName();
    }

    private function fetchShooterData(): ?string
    {
        $shooter = $this->formValues['shooter'] ?? null;
        if (trim($shooter ?? '') === '') {
            return $shooter;
        }

        $shooterEntity = $this->entityManager->getRepository(Shooter::class)->find($shooter);
        if ($shooterEntity === null) {
            return $shooter;
        }

        $this->formValues['firstName'] = $shooterEntity->getFirstName();
        $this->formValues['lastName'] = $shooterEntity->getLastName();
        $this->formValues['email'] = $shooterEntity->getEmail();
        $this->formValues['club'] = $shooterEntity->getClub();

        return $shooter;
    }
}
