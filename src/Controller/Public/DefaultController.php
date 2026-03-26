<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Entity\Competition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    public const string ROUTE_NAME = 'app_index';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(
        path: '/',
        name: self::ROUTE_NAME,
    )]
    public function index(): Response
    {
        $competitions = $this->entityManager->getRepository(Competition::class)->findPublic();

        return $this->render('public/default/index.html.twig', [
            'competitions' => $competitions,
        ]);
    }
}
