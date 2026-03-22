<?php

declare(strict_types=1);

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    public const string ROUTE_NAME = 'app_logout';

    #[Route(
        path: '/logout',
        name: self::ROUTE_NAME,
    )]
    public function logout(): never
    {
        throw new LogicException('Logout is handled by the firewall.');
    }
}
