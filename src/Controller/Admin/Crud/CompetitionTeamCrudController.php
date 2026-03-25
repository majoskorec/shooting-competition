<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\CompetitionTeam;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class CompetitionTeamCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return CompetitionTeam::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Družstvo');
        $crud->setEntityLabelInPlural('Družstvá');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Družstvá');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nové Družstvo');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava Družstva');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail Družstva');

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield AssociationField::new('competition', 'Súťaž')
            ->setFormTypeOption('choice_label', 'name')
            ->hideWhenUpdating();

        yield TextField::new('name', 'Názov');

        yield AssociationField::new('members', 'Členovia')
            ->onlyOnIndex();

        yield CollectionField::new('members', 'Členovia');
    }

    #[Override]
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb = $qb->join('entity.competition', 'c');
        $qb = $qb->addSelect('c');
        $qb = $qb->leftJoin('entity.members', 'm');
        $qb = $qb->addSelect('m');
        $qb = $qb->leftJoin('m.shooter', 's');
        $qb = $qb->addSelect('s');

        return $qb;
    }
}
