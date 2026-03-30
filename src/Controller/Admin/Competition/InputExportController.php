<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition;

use App\Competition\Draw\Exception\MissingStartNumberException;
use App\Competition\Input\InputFactory;
use App\Entity\Competition;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class InputExportController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_input_export';

    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly SluggerInterface $slugger,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/input/export.xls',
        name: self::PART_ROUTE_NAME,
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
    ): Response {
        try {
            $input = $this->inputFactory->createInput($competition);
        } catch (MissingStartNumberException) {
            $this->addFlash(
                'error',
                'Niektorí súťažiaci nemajú pridelené štartovacie číslo. Prosím, opravte to v zozname súťažiacich.',
            );

            return $this->redirectToRoute(InputController::ROUTE_NAME, [
                'entityId' => $competition->getId(),
            ]);
        }

        $content = $this->renderView('admin/competition/input_export/index.xls.twig', [
            'input' => $input,
        ]);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set(
            'Content-Disposition',
            HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $this->createFileName($competition),
            ),
        );

        return $response;
    }

    private function createFileName(Competition $competition): string
    {
        return sprintf(
            '%s-input-%s.xls',
            $this->slugger->slug($competition->getName())->lower()->toString(),
            $competition->getCompetitionStart()->format('Y-m-d'),
        );
    }
}
