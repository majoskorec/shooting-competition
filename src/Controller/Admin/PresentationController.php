<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PresentationController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_competition_presentation';

    #[Route(
        path: '/admin/competition/{id}/presentation',
        name: self::ROUTE_NAME,
    )]
    public function __invoke(Competition $competition, Request $request): Response
    {
        return $this->render('admin/presentation/index.html.twig', [
            'competition' => $competition,
        ]);
    }
}
