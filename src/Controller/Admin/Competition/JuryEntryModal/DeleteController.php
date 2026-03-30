<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition\JuryEntryModal;

use App\Competition\Results\CategorySluggerInterface;
use App\Controller\Admin\Competition\ResultsController;
use App\Entity\JuryEntry;
use App\Form\Dto\JuryEntryDeleteDto;
use App\Form\Type\JuryEntryDeleteType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            assert($data instanceof JuryEntryDeleteDto);
            $id = $data->id;
            if ($id === (string) $juryEntry->getId()) {
                $this->entityManager->remove($juryEntry);
                $this->entityManager->flush();

                return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
                    'categorySlug' => $slug,
                    'entityId' => $competitionId,
                ]);
            }

            $this->addFlash('error', 'JuryEntry could not be deleted. Invalid Id');
        }

        return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
            'categorySlug' => $slug,
            'entityId' => $competitionId,
        ]);
    }

    /**
     * @psalm-return FormInterface
     * @phpstan-return FormInterface<JuryEntryDeleteDto>
     */
    private function createDeleteForm(JuryEntry $juryEntry): FormInterface
    {
        $dto = new JuryEntryDeleteDto();
        $dto->id = $juryEntry->getId();

        return $this->createForm(JuryEntryDeleteType::class, $dto, [
            'action' => $this->generateUrl(self::ROUTE_NAME, [
                'juryEntryId' => $juryEntry->getId(),
            ]),
        ]);
    }
}
