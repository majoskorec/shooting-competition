<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Results\CategoryProvider;
use App\Competition\Results\Model\Category;
use App\Competition\Results\ResultsFactory;
use App\Entity\Competition;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ResultsExportController extends AbstractController
{
    public const string ROUTE_NAME = 'app_competition_results_export';

    public function __construct(
        private readonly CategoryProvider $categoriesProvider,
        private readonly ResultsFactory $resultsFactory,
        private readonly SluggerInterface $slugger,
    ) {
    }

    #[Route(
        path: '/{entityId}/results/{categorySlug}/export.xls',
        name: self::ROUTE_NAME,
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
        string $categorySlug,
    ): Response {
        if ($competition->getStatus() !== CompetitionStatus::Finished && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $categories = $this->categoriesProvider->allForAdmin($competition);
        $category = $categories->getByText($categorySlug);
        if ($category->slug !== $categorySlug) {
            return $this->redirectToRoute(self::ROUTE_NAME, [
                'entityId' => $competition->getId(),
                'categorySlug' => $category->slug,
            ]);
        }

        $results = $this->resultsFactory->create($competition, $category);
        $content = $this->renderView('public/results_export/index.xls.twig', [
            'results' => $results,
        ]);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set(
            'Content-Disposition',
            HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $this->createFileName($competition, $category),
            ),
        );

        return $response;
    }

    private function createFileName(Competition $competition, Category $category): string
    {
        return sprintf(
            '%s-%s-%s.xls',
            $this->slugger->slug($competition->getName())->lower()->toString(),
            $this->slugger->slug($category->title)->lower()->toString(),
            $competition->getCompetitionStart()->format('Y-m-d'),
        );
    }
}
