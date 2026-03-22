<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard';

    #[Route(
        path: '/admin',
        name: self::ROUTE_NAME,
    )]
    public function __invoke(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
