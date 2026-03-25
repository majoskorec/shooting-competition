<?php

declare(strict_types=1);

namespace App\Controller\Admin\Competition;

use App\Entity\Competition;
use App\Model\Factory\InputFactory;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class InputController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_input';

    public function __construct(
        private readonly InputFactory $inputFactory,
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
        $input = $this->inputFactory->createInput($competition);

        return $this->render('admin/competition/input/index.html.twig', [
            'input' => $input,
        ]);
    }
}
