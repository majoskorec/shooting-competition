<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminRoute('/competition')]
final class StartingListController extends AbstractController
{
    public const string ROUTE_NAME = 'admin_dashboard_' . self::PART_ROUTE_NAME;
    private const string PART_ROUTE_NAME = 'competition_staring_list';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[AdminRoute(
        path: '/{entityId}/staring-list',
        name: self::PART_ROUTE_NAME,
    )]
    public function __invoke(
        #[MapEntity(id: 'entityId')]
        Competition $competition,
    ): Response {
        $competitors = $this->entityManager->getRepository(Competitor::class)
            ->createQueryBuilder('c')
            ->select(['c', 's', 't', 'cat'])
            ->join('c.shooter', 's')
            ->leftJoin('c.competitionTeam', 't')
            ->leftJoin('c.categories', 'cat')
            ->andWhere('c.competition = :competition')
            ->setParameter('competition', $competition)
            ->addOrderBy('c.startNumber', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/stating_list/index.html.twig', [
            'competition' => $competition,
            'competitors' => $competitors,
        ]);
    }
}
