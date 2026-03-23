<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CompetitionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class CompetitionTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CompetitionType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Competition Type')
            ->setEntityLabelInPlural('Competition Types')
            ->setPageTitle(Crud::PAGE_INDEX, 'Competition Types')
            ->setPageTitle(Crud::PAGE_NEW, 'Create Competition Type')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Competition Type')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Competition Type Detail');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name');

        yield TextareaField::new('description')
            ->hideOnIndex()
            ->setRequired(false);

        yield CollectionField::new('targets', 'Targets')
            ->useEntryCrudForm(CompetitionTypeTargetCrudController::class);
    }
}
