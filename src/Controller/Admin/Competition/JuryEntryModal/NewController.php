<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition\JuryEntryModal;

use App\Competition\Results\CategorySluggerInterface;
use App\Controller\Admin\Competition\ResultsController;
use App\Entity\CompetitionCategory;
use App\Entity\Competitor;
use App\Entity\JuryEntry;
use App\Form\Type\JuryEntryType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class NewController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_jury_entry_new';

    public function __construct(
        private readonly CategorySluggerInterface $categorySlugger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AdminRoute(
        path: '/jury-entry/new/{competitorId}/{categorySlug}',
        name: self::PART_ROUTE_NAME,
        options: [
            'methods' => [
                Request::METHOD_GET,
                Request::METHOD_POST,
            ],
        ],
    )]
    public function __invoke(
        #[MapEntity(id: 'competitorId')]
        Competitor $competitor,
        string $categorySlug,
        Request $request,
    ): Response {
        $category = $this->resolveCompetitionCategory($competitor, $categorySlug);

        $juryEntry = new JuryEntry();
        $juryEntry->setCompetitor($competitor);
        $juryEntry->setCategory($category);

        $form = $this->createForm(JuryEntryType::class, $juryEntry, [
            'action' => $request->getUri(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($juryEntry);
            $this->entityManager->flush();

            return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
                'entityId' => $competitor->getCompetition()->getId(),
                'categorySlug' => $categorySlug,
            ]);
        }

        return $this->render('admin/competition/jury_entry_modal/new/index.html.twig', [
                'jury_entry' => $juryEntry,
                'form' => $form,
            ],
            new Response(
                status: $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY
                    : Response::HTTP_OK,
            ),
        );
    }

    private function resolveCompetitionCategory(Competitor $competitor, string $categorySlug): ?CompetitionCategory
    {
        if ($categorySlug === $this->categorySlugger->slugCategoryName($competitor->getCompetition()->getMainCategoryName())) {
            return null;
        }

        foreach ($competitor->getCategories() as $competitionCategory) {
            if ($categorySlug === $this->categorySlugger->slugCategoryName($competitionCategory->getName())) {
                return $competitionCategory;
            }
        }

        throw $this->createNotFoundException('Competition category not found.');
    }
}
