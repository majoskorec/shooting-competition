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

final class ResultsController extends AbstractController
{
    public const string ROUTE_NAME = 'app_results';

    public function __construct(
        private readonly CategoryProvider $categoriesProvider,
        private readonly ResultsFactory $resultsFactory,
    ) {
    }

    #[Route(
        path: '/sutaz/{id}/vysledky/{categorySlug}',
        name: self::ROUTE_NAME,
        defaults: ['categorySlug' => null],
    )]
    public function index(
        Competition $competition,
        ?string $categorySlug = null,
    ): Response {
        if (!$competition->getStatus()->isPublished()) {
            return $this->redirectToRoute(DefaultController::ROUTE_NAME);
        }

        $categories = $this->categoriesProvider->allForPublic($competition);
        $category = $categories->getByText($categorySlug ?? '');
        if ($category->slug !== $categorySlug) {
            return $this->redirectToRoute(self::ROUTE_NAME, [
                'id' => $competition->getId(),
                'categorySlug' => $category->slug,
            ]);
        }

        $results = $this->resultsFactory->create($competition, $category);

        return $this->render('public/results/index.html.twig', [
            'results' => $results,
        ]);
    }
}
