<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PresentationController extends AbstractController
{
    public const string ROUTE_NAME = 'app_presentation';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(
        path: '/sutaz/{id}/prezentacia',
        name: self::ROUTE_NAME,
    )]
    public function index(Competition $competition): Response
    {
        if (!$competition->getStatus()->isPublished()) {
            return $this->redirectToRoute(DefaultController::ROUTE_NAME);
        }

        $competitors = $this->entityManager->getRepository(Competitor::class)->findForPresentation($competition);

        return $this->render('public/presentation/index.html.twig', [
            'competition' => $competition,
            'competitors' => $competitors,
        ]);
    }
}
