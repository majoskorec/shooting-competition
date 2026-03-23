<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CompetitionTypeTarget;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

final class CompetitionTypeTargetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CompetitionTypeTarget::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Competition Type Target')
            ->setEntityLabelInPlural('Competition Type Targets')
            ->setPageTitle(Crud::PAGE_INDEX, 'Competition Type Targets')
            ->setPageTitle(Crud::PAGE_NEW, 'Create Competition Type Target')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Competition Type Target')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Competition Type Target Detail')
            ->setDefaultSort(['competitionType' => 'ASC', 'displayOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield AssociationField::new('competitionType')
            ->setFormTypeOption('choice_label', 'name');

        yield AssociationField::new('targetDefinition')
            ->setFormTypeOption('choice_label', 'name');

        yield IntegerField::new('displayOrder');
        yield IntegerField::new('shotCount');

        yield IntegerField::new('tieBreakPriority')
            ->setRequired(false);
    }
}
