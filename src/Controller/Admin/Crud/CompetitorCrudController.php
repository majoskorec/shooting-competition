<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Competition\Model\CompetitorStatus;
use App\Entity\Competitor;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CompetitorCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

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

        yield AssociationField::new('competition', 'Súťaž')
            ->setFormTypeOption('choice_label', 'name');

        yield AssociationField::new('shooter', 'Strelec')
            ->setFormTypeOption('choice_label', 'fullName');

        yield NumberField::new('startNumber', 'Štartové číslo')
            ->setRequired(false);

        yield TextField::new('sharedWeaponCode', 'Zdieľaná zbraň')
            ->setRequired(false);

        yield ChoiceField::new('status', 'Stav')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', CompetitorStatus::class)
            ->setFormTypeOption(
                'choice_label',
                fn (CompetitorStatus $choice): string => $choice->trans($this->translator),
            );

        yield NumberField::new('cachedTotalScore', 'Celkové skóre (cache)')
            ->setRequired(false);

        yield AssociationField::new('categories', 'Kategórie')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
    }

    #[Override]
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb = $qb->join('entity.competition', 'c');
        $qb = $qb->addSelect('c');
        $qb = $qb->join('entity.shooter', 's');
        $qb = $qb->addSelect('s');
        $qb = $qb->leftJoin('entity.categories', 'cat');
        $qb = $qb->addSelect('cat');

        return $qb;
    }
}
