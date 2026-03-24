<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Competition;
use App\Form\Type\JsonCodeEditorType;
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

final class CompetitionCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TargetSnapshotFactory $snapshotFactory,
    ) {
    }

    #[Override]
    public static function getEntityFqcn(): string
    {
        return Competition::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Competition');
        $crud->setEntityLabelInPlural('Competitions');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Competitions');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Create Competition');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Edit Competition');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Competition Detail');
        $crud->setDefaultSort(['competitionStart' => 'DESC']);

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name');

        yield AssociationField::new('competitionType')
            ->setFormTypeOption('choice_label', 'name')
            ->hideWhenUpdating();

        yield DateTimeField::new('competitionStart', 'Competition Start')
            ->setFormat('yyyy-MM-dd HH:mm')
            ->setTimezone('Europe/Bratislava')
            ->renderAsNativeWidget()
            ->setFormTypeOption('model_timezone', 'UTC')
            ->setFormTypeOption('view_timezone', 'Europe/Bratislava')
            ->setFormTypeOption('with_seconds', false);

        yield TextareaField::new('description')
            ->setRequired(false)
            ->hideOnIndex();

        yield TextField::new('location')
            ->setRequired(false);

        yield TextField::new('organizer')
            ->setRequired(false);

        yield ChoiceField::new('status')
            ->setChoices(CompetitionStatus::cases());

        yield NumberField::new('teamMemberCount');

        yield NumberField::new('shootersInRound');

        yield CodeEditorField::new('targetConfigurationSnapshot', 'Target Configuration Snapshot')
            ->setLanguage('javascript')
            ->setNumOfRows(18)
            ->setFormType(JsonCodeEditorType::class)
            ->hideWhenCreating()
            ->formatValue(static function (mixed $value): string {
                if (!is_array($value)) {
                    return '';
                }

                return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            });

    }

    #[Override]
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Competition) {
            $entityInstance->setTargetConfigurationSnapshot(
                $this->snapshotFactory->createFromCompetitionType($entityInstance->getCompetitionType()),
            );
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
