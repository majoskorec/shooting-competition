<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition\JuryEntryModal;

use App\Competition\Results\CategorySluggerInterface;
use App\Controller\Admin\Competition\ResultsController;
use App\Entity\JuryEntry;
use App\Form\Dto\JuryEntryDeleteDto;
use App\Form\Type\JuryEntryDeleteType;
use App\Form\Type\JuryEntryType;
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
final class EditController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_jury_entry_edit';

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
                Request::METHOD_GET,
                Request::METHOD_PUT,
            ],
        ],
    )]
    public function edit(
        #[MapEntity(id: 'juryEntryId')]
        JuryEntry $juryEntry,
        Request $request,
    ): Response {
        $competitionId = $juryEntry->getCompetitor()->getCompetition()->getId();
        $slug = $this->categorySlugger->slugCategoryName($juryEntry->getCategoryName());

        $form = $this->createForm(JuryEntryType::class, $juryEntry, [
            'action' => $request->getUri(),
            'method' => Request::METHOD_PUT,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($juryEntry);
            $this->entityManager->flush();

            return $this->redirectToRoute(ResultsController::ROUTE_NAME, [
                'categorySlug' => $slug,
                'entityId' => $competitionId,
            ]);
        }

        return $this->render(
            'admin/competition/jury_entry_modal/edit/index.html.twig',
            [
                'delete_form' => $this->createDeleteForm($juryEntry),
                'form' => $form,
                'jury_entry' => $juryEntry,
            ],
            new Response(
                status: $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY
                    : Response::HTTP_OK,
            ),
        );
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
            'action' => $this->generateUrl(DeleteController::ROUTE_NAME, [
                'juryEntryId' => $juryEntry->getId(),
            ]),
        ]);
    }
}
