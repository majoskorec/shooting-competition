<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\CompetitionCategory;
use App\Entity\Competitor;
use App\Entity\JuryEntry;
use App\Repository\CompetitionCategoryRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Error;
use Override;

/**
 * @extends AbstractCrudController<JuryEntry>
 */
final class JuryEntryCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return JuryEntry::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Rozstrel');
        $crud->setEntityLabelInPlural('Rozstrely');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Rozstrely');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nový Rozstrel');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava Rozstrelu');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail Rozstrelu');

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield AssociationField::new('competitor', 'Súťažiaci')
            ->setFormTypeOption('choice_label', static fn (Competitor $competitor): string => sprintf(
                '%s (%s)',
                $competitor->getShooter()->getFullName(),
                $competitor->getCompetition()->getName(),
            ));

        yield AssociationField::new('category', 'Kategória')
            ->setRequired(false)
            ->setHelp('Ak je prázdne, položka sa vzťahuje na celkové poradie.')
            ->setFormTypeOption('choice_label', static fn (CompetitionCategory $category): string => sprintf(
                '%s (%s)',
                $category->getName(),
                $category->getCompetition()->getName(),
            ))
            ->setFormTypeOption(
                'query_builder',
                function (CompetitionCategoryRepository $repository): QueryBuilder {
                    $queryBuilder = $repository->createQueryBuilder('category')
                        ->orderBy('category.name', 'ASC');

                    $juryEntry = $this->getContext()?->getEntity()?->getInstance();
                    if (!$juryEntry instanceof JuryEntry) {
                        return $queryBuilder;
                    }

                    try {
                        $competitor = $juryEntry->getCompetitor();
                    } catch (Error) {
                        return $queryBuilder;
                    }

                    return $queryBuilder
                        ->andWhere('category.competition = :competition')
                        ->setParameter('competition', $competitor->getCompetition());
                },
            );

        yield NumberField::new('points', 'Body');

        yield TextareaField::new('description', 'Popis')
            ->setRequired(false)
            ->hideOnIndex();
    }

    #[Override]
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb = $qb->join('entity.competitor', 'competitor');
        $qb = $qb->addSelect('competitor');
        $qb = $qb->join('competitor.shooter', 'shooter');
        $qb = $qb->addSelect('shooter');
        $qb = $qb->join('competitor.competition', 'competition');
        $qb = $qb->addSelect('competition');
        $qb = $qb->leftJoin('entity.category', 'category');
        $qb = $qb->addSelect('category');

        return $qb;
    }
}
