<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CompetitionTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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

        yield AssociationField::new('competition')
            ->setFormTypeOption('choice_label', 'name')
            ->hideWhenUpdating();

        yield TextField::new('name');

        yield AssociationField::new('members')
            ->onlyOnIndex();

        yield CollectionField::new('members');
    }
}
