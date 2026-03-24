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
            ->setEntityLabelInSingular('Terč typu súťaže')
            ->setEntityLabelInPlural('Terče typov súťaží')
            ->setPageTitle(Crud::PAGE_INDEX, 'Terče typov súťaží')
            ->setPageTitle(Crud::PAGE_NEW, 'Nový terč typu súťaže')
            ->setPageTitle(Crud::PAGE_EDIT, 'Úprava terča typu súťaže')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Detail terča typu súťaže')
            ->setDefaultSort(['competitionType' => 'ASC', 'displayOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield AssociationField::new('competitionType', 'Typ súťaže')
            ->setFormTypeOption('choice_label', 'name');

        yield AssociationField::new('targetDefinition', 'Definícia terča')
            ->setFormTypeOption('choice_label', 'name');

        yield IntegerField::new('displayOrder', 'Poradie zobrazenia');
        yield IntegerField::new('shotCount', 'Počet rán');

        yield IntegerField::new('tieBreakPriority', 'Priorita pri rovnosti')
            ->setRequired(false);
    }
}
