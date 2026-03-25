<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\TargetDefinition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class TargetDefinitionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TargetDefinition::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Definícia terča')
            ->setEntityLabelInPlural('Definície terčov')
            ->setPageTitle(Crud::PAGE_INDEX, 'Definície terčov')
            ->setPageTitle(Crud::PAGE_NEW, 'Nová definícia terča')
            ->setPageTitle(Crud::PAGE_EDIT, 'Úprava definície terča')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Detail definície terča');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Názov');

        yield CollectionField::new('pointsSchema', 'Bodovacia schéma')
            ->setHelp('Usporiadaný zoznam povolených bodových hodnôt pre tento terč.')
            ->setEntryType(IntegerType::class)
            ->allowAdd()
            ->allowDelete()
            ->renderExpanded()
            ->formatValue(function ($value, $entity) {
                if (is_array($value)) {
                    return implode(', ', $value);
                }

                return $value;
            });
    }
}
