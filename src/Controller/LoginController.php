<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Admin\DashboardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    public const string ROUTE_NAME = 'app_login';

    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
    ) {
    }

    #[Route(
        path: '/login',
        name: self::ROUTE_NAME,
    )]
    public function index(): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute(DashboardController::ROUTE_NAME);
        }

        return $this->render('login/index.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }
}
