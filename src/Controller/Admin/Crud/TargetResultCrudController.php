<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\Competitor;
use App\Entity\TargetResult;
use App\Form\Type\JsonCodeEditorType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

final class TargetResultCrudController extends AbstractCrudController
{
    #[Override]
    public static function getEntityFqcn(): string
    {
        return TargetResult::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Výsledok na terči');
        $crud->setEntityLabelInPlural('Výsledky na terčoch');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Výsledky na terčoch');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nový výsledok na terči');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava výsledku na terči');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail výsledku na terči');
        $crud->setDefaultSort(['id' => 'DESC']);

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('competitor.competition', 'Súťaž')->onlyOnIndex();

        yield AssociationField::new('competitor', 'Súťažiaci')
            ->setFormTypeOption(
                'choice_label',
                static fn (Competitor $competitor): string => sprintf(
                    '%s / %s / #%s',
                    $competitor->getCompetition()->getName(),
                    $competitor->getShooter()->getFullName(),
                    $competitor->getStartNumber() ?? '-',
                ),
            );

        yield TextField::new('targetName', 'Terč');

        yield CodeEditorField::new('hitBreakdown', 'Rozpis zásahov')
            ->setLanguage('javascript')
            ->setNumOfRows(12)
            ->setFormType(JsonCodeEditorType::class)
            ->formatValue($this->formatJsonValue(...));

        yield NumberField::new('subtotal', 'Subtotal')
            ->hideOnForm();

        yield BooleanField::new('consistent', 'Konzistentný');

        yield CodeEditorField::new('validationIssues', 'Validačné problémy')
            ->setLanguage('javascript')
            ->setNumOfRows(8)
            ->setRequired(false)
            ->setFormType(JsonCodeEditorType::class)
            ->formatValue($this->formatJsonValue(...));
    }

    #[Override]
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb = $qb->join('entity.competitor', 'c');
        $qb = $qb->addSelect('c');
        $qb = $qb->join('c.shooter', 's');
        $qb = $qb->addSelect('s');
        $qb = $qb->join('c.competition', 'competition');
        $qb = $qb->addSelect('competition');
        $qb = $qb->addOrderBy('competition.competitionStart', 'DESC');
        $qb = $qb->addOrderBy('c.startNumber', 'ASC');
        $qb = $qb->addOrderBy('entity.targetName', 'ASC');

        return $qb;
    }

    #[Override]
    public function configureFilters(Filters $filters): Filters
    {
        $filters->add('targetName');

        return $filters;
    }

    private function formatJsonValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (!is_array($value)) {
            return (string) $value;
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
