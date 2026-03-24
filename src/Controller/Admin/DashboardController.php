<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CompetitionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Override;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(
    routePath: '/admin',
    routeName: DashboardController::ROUTE_NAME,
)]
final class DashboardController extends AbstractDashboardController
{
    public const string ROUTE_NAME = 'admin_dashboard';

    public function __construct(
        private readonly CompetitionRepository $competitionRepository,
    ) {
    }


    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Shooting Competition');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'internal:home');

        $definitions = MenuItem::subMenu('Definitions', 'fa-solid fa-gear');
        $definitions->setSubItems([
            MenuItem::linkTo(TargetDefinitionCrudController::class, 'Target Definitions', 'fas fa-bullseye'),
            MenuItem::linkTo(CompetitionTypeTargetCrudController::class, 'Competition Type Targets', 'fas fa-crosshairs'),
            MenuItem::linkTo(CompetitionTypeCrudController::class, 'Competition Types', 'fas fa-list'),
        ]);

        yield $definitions;

        yield MenuItem::linkTo(ShooterCrudController::class, 'Shooters', 'fa-solid fa-person-rifle');

        yield MenuItem::section('Competitions', 'fa-solid fa-chess');

        yield MenuItem::linkTo(CompetitionCrudController::class, 'Competitions', 'fas fa-trophy');
        yield MenuItem::linkTo(CompetitionEntryCrudController::class, 'Competitions Entry', 'fas fa-trophy');


        $activeCompetitions = $this->competitionRepository->findActive();
        if (count($activeCompetitions) === 0) {
            return;
        }

        yield MenuItem::section('Active Competitions', 'fa-solid fa-chess');

        foreach ($activeCompetitions as $competition) {
            yield MenuItem::section($competition->getName(), 'fa-solid fa-arrows-to-dot');
            yield MenuItem::linkToRoute(
                'Presentation',
                '',
                PresentationController::ROUTE_NAME,
                ['entityId' => $competition->getId()],
            );
        }
    }

    #[Override]
    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();
        $crud->setDefaultRowAction(Action::DETAIL);

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
