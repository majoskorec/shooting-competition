<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(
    routePath: '/admin',
    routeName: DashboardController::ROUTE_NAME,
)]
final class DashboardController extends AbstractDashboardController
{
    public const string ROUTE_NAME = 'admin_dashboard';

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
    }
}
