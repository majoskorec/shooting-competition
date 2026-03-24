<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competition;
use App\Entity\CompetitionEntry;
use App\Form\Type\JsonCodeEditorType;
use App\Model\Enum\CompetitionEntryStatus;
use App\Model\Enum\CompetitionStatus;
use App\Model\Factory\TargetSnapshotFactory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class CompetitionEntryCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return CompetitionEntry::class;
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
            ->setFormTypeOption('choice_label', 'name')
            ->hideWhenUpdating();

        yield AssociationField::new('shooter')
            ->setFormTypeOption('choice_label', 'fullName')
            ->hideWhenUpdating();

        yield NumberField::new('startNumber')
            ->setRequired(false);

        yield TextField::new('sharedWeaponCode')
            ->setRequired(false);

        yield ChoiceField::new('status')
            ->setChoices(CompetitionEntryStatus::cases());

        yield NumberField::new('cachedTotalScore')
            ->setRequired(false);
    }
}
