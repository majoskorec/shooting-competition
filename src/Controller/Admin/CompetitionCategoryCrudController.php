<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CompetitionCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class CompetitionCategoryCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return CompetitionCategory::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Kategória');
        $crud->setEntityLabelInPlural('Kategórie');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Kategórie');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nová Kategória');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava Kategórie');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail Kategórie');

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

        yield AssociationField::new('competitors', 'Súťažiaci')
            ->onlyOnIndex();
    }
}
