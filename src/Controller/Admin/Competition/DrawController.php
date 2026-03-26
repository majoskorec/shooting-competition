<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition;

use App\Competition\Draw\DrawService;
use App\Competition\Draw\Exception\StartNumberAssignmentException;
use App\Competition\Model\CompetitionStatus;
use App\Entity\Competition;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class DrawController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_draw';

    public function __construct(
        private readonly DrawService $drawService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/draw',
        name: self::PART_ROUTE_NAME,
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
    ): Response {
        try {
            ($this->drawService)($competition);
            $competition->setStatus(CompetitionStatus::InProgress);
            $this->entityManager->flush();
            $this->addFlash('success', 'Rozlosovanie a pridelenie štartových čísel bolo dokončené.');
        } catch (StartNumberAssignmentException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        if ($competition->getStatus() === CompetitionStatus::InProgress) {
            return $this->redirectToRoute(StartingListController::ROUTE_NAME, [
                'entityId' => $competition->getId(),
            ]);
        }

        return $this->redirectToRoute(PresentationController::ROUTE_NAME, [
            'entityId' => $competition->getId(),
        ]);
    }
}
