<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition;

use App\Competition\Draw\Exception\MissingStartNumberException;
use App\Competition\Input\InputFactory;
use App\Entity\Competition;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class InputController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_input';

    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/input',
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

            return $this->redirectToRoute(PresentationController::ROUTE_NAME, [
                'entityId' => $competition->getId(),
            ]);
        }

        $constraintViolationList = $this->validator->validate($input);

        return $this->render('admin/competition/input/index.html.twig', [
            'input' => $input,
            'inputConstraints' => $constraintViolationList,
        ]);
    }
}
