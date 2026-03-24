<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competitor;
use App\Model\Enum\CompetitorStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class CompetitorCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return Competitor::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Súťažiaci');
        $crud->setEntityLabelInPlural('Súťažiaci');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Súťažiaci');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nový Súťažiaci');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava Súťažiaceho');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail Súťažiaceho');

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield AssociationField::new('competition')
            ->setFormTypeOption('choice_label', 'name');

        yield AssociationField::new('shooter')
            ->setFormTypeOption('choice_label', 'fullName');

        yield NumberField::new('startNumber')
            ->setRequired(false);

        yield TextField::new('sharedWeaponCode')
            ->setRequired(false);

        yield ChoiceField::new('status')
            ->setChoices(CompetitorStatus::cases());

        yield NumberField::new('cachedTotalScore')
            ->setRequired(false);
    }
}
