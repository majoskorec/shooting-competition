<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CompetitionType;
use App\Model\Enum\CompetitionStatus;
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
        $dashboard = Dashboard::new();
        $dashboard->setTitle('Strelecká súťaž');
        $dashboard->renderContentMaximized();

        return $dashboard;
    }

    public function configureMenuItems(): iterable
    {
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
        yield MenuItem::linkTo(CompetitionTeamCrudController::class, 'Družstvá', 'fa-solid fa-people-group');


        $activeCompetitions = $this->competitionRepository->findActive();
        if (count($activeCompetitions) === 0) {
            return;
        }

        yield MenuItem::section('Aktívne súťaže', 'fa-solid fa-chess');

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
