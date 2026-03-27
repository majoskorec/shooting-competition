<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition;

use App\Competition\Results\CategoryProvider;
use App\Competition\Results\ResultsFactory;
use App\Entity\Competition;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class ResultsController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_results';

    public function __construct(
        private readonly CategoryProvider $categoriesProvider,
        private readonly ResultsFactory $resultsFactory,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/results/{categorySlug}',
        name: self::PART_ROUTE_NAME,
        options: [
            'defaults' => ['categorySlug' => null],
        ],
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
        ?string $categorySlug = null,
    ): Response {
        $categories = $this->categoriesProvider->allForAdmin($competition);
        $category = $categories->getByText($categorySlug ?? '');
        if ($category->slug !== $categorySlug) {
            return $this->redirectToRoute(self::ROUTE_NAME, [
                'entityId' => $competition->getId(),
                'categorySlug' => $category->slug,
            ]);
        }

        $results = $this->resultsFactory->create($competition, $category);

        return $this->render('admin/competition/results/index.html.twig', [
            'categories' => $categories,
            'results' => $results,
        ]);
    }
}
