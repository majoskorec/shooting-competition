<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Results\CategoryProvider;
use App\Competition\Results\ResultsFactory;
use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PartialResultsController extends AbstractController
{
    public const string ROUTE_NAME = 'app_partial_results';

    public function __construct(
        private readonly CategoryProvider $categoriesProvider,
        private readonly ResultsFactory $resultsFactory,
    ) {
    }

    #[Route(
        path: '/sutaz/{id}/priebezne-vysledky',
        name: self::ROUTE_NAME,
    )]
    public function index(
        Competition $competition,
    ): Response {
        if ($competition->getStatus() === CompetitionStatus::Finished) {
            return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
                'id' => $competition->getId(),
            ]);
        }

        $category = $this->categoriesProvider->getPartialResults($competition);
        $results = $this->resultsFactory->create($competition, $category);

        return $this->render('public/partial_results/index.html.twig', [
            'results' => $results,
        ]);
    }
}
