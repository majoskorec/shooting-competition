<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Shooter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class ShooterCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return Shooter::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Strelec');
        $crud->setEntityLabelInPlural('Strelci');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Strelci');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nový Strelec');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava Strelca');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail Strelca');
        $crud->setDefaultSort(['lastName' => 'ASC', 'firstName' => 'ASC']);

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('firstName', 'Meno');

        yield TextField::new('lastName', 'Priezvisko');

        yield TextField::new('club', 'Klub / PZ')
            ->setRequired(false);

        yield EmailField::new('email', 'E-mail')
            ->setRequired(false);
    }
}
