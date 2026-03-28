<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition\JuryEntryModal;

use App\Competition\Results\CategorySluggerInterface;
use App\Controller\Admin\Competition\ResultsController;
use App\Entity\JuryEntry;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class DeleteController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_jury_entry_delete';

    public function __construct(
        private readonly CategorySluggerInterface $categorySlugger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AdminRoute(
        path: '/jury-entry/{juryEntryId}',
        name: self::PART_ROUTE_NAME,
        options: [
            'methods' => [
                Request::METHOD_DELETE,
            ],
        ],
    )]
    public function __invoke(
        #[MapEntity(id: 'juryEntryId')]
        JuryEntry $juryEntry,
        Request $request,
    ): Response {
        $competitionId = $juryEntry->getCompetitor()->getCompetition()->getId();
        $slug = $this->categorySlugger->slugCategoryName($juryEntry->getCategoryName());

        $form = $this->createDeleteForm($juryEntry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $id = $data['id'] ?? null;
            if ($id === (string) $juryEntry->getId()) {
                $this->entityManager->remove($juryEntry);
                $this->entityManager->flush();

                return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
                    'entityId' => $competitionId,
                    'categorySlug' => $slug,
                ]);
            }

            $this->addFlash('error', 'JuryEntry could not be deleted. Invalid Id');
        }

        return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
            'entityId' => $competitionId,
            'categorySlug' => $slug,
        ]);
    }

    private function createDeleteForm(JuryEntry $juryEntry): FormInterface
    {
        $formBuilder = $this->createFormBuilder(['id' => $juryEntry->getId()], [
            'action' => $this->generateUrl(self::ROUTE_NAME, [
                'juryEntryId' => $juryEntry->getId(),
            ]),
            'method' => Request::METHOD_DELETE,
        ]);
        $formBuilder->add('id', HiddenType::class);

        return $formBuilder->getForm();
    }
}
