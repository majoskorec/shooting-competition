<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Competition\Model\CompetitionStatus;
use App\Controller\Admin\Competition\InputController;
use App\Controller\Admin\Competition\PresentationController;
use App\Controller\Admin\Competition\ResultsController;
use App\Controller\Admin\Competition\StartingListController;
use App\Controller\Admin\Crud\CompetitionCategoryCrudController;
use App\Controller\Admin\Crud\CompetitionCrudController;
use App\Controller\Admin\Crud\CompetitionTeamCrudController;
use App\Controller\Admin\Crud\CompetitionTypeCrudController;
use App\Controller\Admin\Crud\CompetitionTypeTargetCrudController;
use App\Controller\Admin\Crud\CompetitorCrudController;
use App\Controller\Admin\Crud\ShooterCrudController;
use App\Controller\Admin\Crud\TargetResultCrudController;
use App\Controller\Admin\Crud\TargetDefinitionCrudController;
use App\Controller\Admin\Crud\UserCrudController;
use App\Controller\Public\DefaultController;
use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Override;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AdminDashboard(
    routePath: '/admin',
    routeName: DashboardController::ROUTE_NAME,
)]
final class DashboardController extends AbstractDashboardController
{
    public const string ROUTE_NAME = 'admin_dashboard';

    public function __construct(
        private readonly CompetitionRepository $competitionRepository,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function index(): Response
    {
        $competitions = $this->entityManager->getRepository(Competition::class)->findBy(
            criteria: [],
            orderBy: ['competitionStart' => 'DESC'],
            limit: 20,
        );

        return $this->render('admin/dashboard/index.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new();
        $dashboard->setTitle('Strelecká súťaž');
        $dashboard->renderContentMaximized();
        $dashboard->setFaviconPath('favicon.png');

        return $dashboard;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Web', 'fa-solid fa-globe', $this->urlGenerator->generate(DefaultController::ROUTE_NAME))
            ->setLinkTarget('_blank');

        yield MenuItem::linkToDashboard('Nástenka', 'internal:home');

        $definitions = MenuItem::subMenu('Definície', 'fa-solid fa-gear');
        $definitions->setSubItems([
            MenuItem::linkTo(TargetDefinitionCrudController::class, 'Definície terčov', 'fas fa-bullseye'),
            MenuItem::linkTo(CompetitionTypeTargetCrudController::class, 'Terče typov súťaží', 'fas fa-crosshairs'),
            MenuItem::linkTo(CompetitionTypeCrudController::class, 'Typy súťaží', 'fas fa-list'),
        ]);

        yield $definitions;

        yield MenuItem::linkTo(ShooterCrudController::class, 'Strelci', 'fa-solid fa-person-rifle');
        yield MenuItem::linkTo(CompetitionCrudController::class, 'Súťaže', 'fa-solid fa-trophy');
        yield MenuItem::linkTo(CompetitorCrudController::class, 'Súťažiaci', 'fa-solid fa-user');
        yield MenuItem::linkTo(TargetResultCrudController::class, 'Výsledky na terčoch', 'fa-solid fa-table-cells');
        yield MenuItem::linkTo(CompetitionTeamCrudController::class, 'Družstvá', 'fa-solid fa-people-group');
        yield MenuItem::linkTo(CompetitionCategoryCrudController::class, 'Kategórie', 'fa-solid fa-arrows-down-to-people');
        yield MenuItem::linkTo(UserCrudController::class, 'Používatelia', 'fa-solid fa-users-gear');


        $activeCompetitions = $this->competitionRepository->findActive();
        if (count($activeCompetitions) === 0) {
            return;
        }

        yield MenuItem::section('Aktívne súťaže', 'fa-solid fa-chess');

        $request = $this->requestStack->getCurrentRequest();
        $requestRoute = $request?->attributes->get('_route');
        $entityId = $request?->attributes->get('entityId');

        foreach ($activeCompetitions as $competition) {
            yield MenuItem::section($competition->getName(), 'fa-solid fa-arrows-to-dot');
            yield MenuItem::linkToRoute(
                'Prezentácia',
                '',
                PresentationController::ROUTE_NAME,
                ['entityId' => $competition->getId()],
            );
            if ($competition->getStatus() !== CompetitionStatus::Presentation) {
                yield MenuItem::linkToRoute(
                    'Štartová listina',
                    '',
                    StartingListController::ROUTE_NAME,
                    ['entityId' => $competition->getId()],
                );
                yield MenuItem::linkToRoute(
                    'Zadávanie výsledkov',
                    '',
                    InputController::ROUTE_NAME,
                    ['entityId' => $competition->getId()],
                );
                yield MenuItem::linkToRoute(
                    'Výsledky',
                    '',
                    ResultsController::ROUTE_NAME,
                    [
                        'entityId' => $competition->getId(),
                    ],
                )->setCssClass(
                    $requestRoute === ResultsController::ROUTE_NAME && (int) $entityId === $competition->getId()
                        ? 'active'
                        : '',
                );
            }
        }
    }

    #[Override]
    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();
        $crud->setDefaultRowAction(Action::DETAIL);
        $crud->showEntityActionsInlined();
        $crud->renderContentMaximized();
        $crud->setPaginatorPageSize(200);

        return $crud;
    }

    #[Override]
    public function configureActions(): Actions
    {
        $actions = parent::configureActions();
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();
        $assets->addAssetMapperEntry('admin');

        return $assets;
    }
}
