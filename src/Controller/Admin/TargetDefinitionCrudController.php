<?php

declare(strict_types=1);

namespace App\Controller\Admin;

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
            ->setEntityLabelInSingular('Target Definition')
            ->setEntityLabelInPlural('Target Definitions')
            ->setPageTitle(Crud::PAGE_INDEX, 'Target Definitions')
            ->setPageTitle(Crud::PAGE_NEW, 'Create Target Definition')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Target Definition')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Target Definition Detail');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name');

        yield CollectionField::new('pointsSchema', 'Points Schema')
            ->setHelp('Ordered list of allowed point values for this target.')
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
