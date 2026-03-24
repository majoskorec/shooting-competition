<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competition;
use App\Entity\CompetitionEntry;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class PresentationController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_presentation';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/presentation',
        name: self::PART_ROUTE_NAME,
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
    ): Response {
        $competitors = $this->entityManager->getRepository(CompetitionEntry::class)
            ->createQueryBuilder('c')
            ->join('c.shooter', 's')
            ->andWhere('c.competition = :competition')
            ->setParameter('competition', $competition)
            ->addOrderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/presentation/index.html.twig', [
            'competition' => $competition,
            'competitors' => $competitors,
        ]);
    }
}
