<?php

declare(strict_types=1);

namespace App\Controller\Public;

use App\Competition\Model\CompetitorStatus;
use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PresentationController extends AbstractController
{
    public const string ROUTE_NAME = 'app_presentation';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(
        path: '/sutaz/{id}/prezentacia',
        name: self::ROUTE_NAME,
    )]
    public function index(Competition $competition): Response
    {
        $competitors = $this->entityManager->getRepository(Competitor::class)
            ->createQueryBuilder('c')
            ->select(['c', 's', 't', 'cat'])
            ->join('c.shooter', 's')
            ->leftJoin('c.competitionTeam', 't')
            ->leftJoin('c.categories', 'cat')
            ->andWhere('c.competition = :competition')
            ->setParameter('competition', $competition)
            ->andWhere('c.status in (:statuses)')
            ->setParameter('statuses', [
                CompetitorStatus::Registered,
            ])
            ->addOrderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('public/presentation/index.html.twig', [
            'competition' => $competition,
            'competitors' => $competitors,
        ]);
    }
}
