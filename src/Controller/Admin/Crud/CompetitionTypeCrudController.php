<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

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
            ->setEntityLabelInSingular('Typ súťaže')
            ->setEntityLabelInPlural('Typy súťaží')
            ->setPageTitle(Crud::PAGE_INDEX, 'Typy súťaží')
            ->setPageTitle(Crud::PAGE_NEW, 'Nový typ súťaže')
            ->setPageTitle(Crud::PAGE_EDIT, 'Úprava typu súťaže')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Detail typu súťaže');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Názov');

        yield TextareaField::new('description', 'Popis')
            ->hideOnIndex()
            ->setRequired(false);

        yield CollectionField::new('targets', 'Terče')
            ->useEntryCrudForm(CompetitionTypeTargetCrudController::class);
    }
}
