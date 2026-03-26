<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Target\TargetSnapshotFactory;
use App\Entity\Competition;
use App\Form\Type\JsonCodeEditorType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CompetitionCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TargetSnapshotFactory $snapshotFactory,
        private readonly TranslatorInterface $translator,
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
        $crud->setEntityLabelInSingular('Súťaž');
        $crud->setEntityLabelInPlural('Súťaže');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Súťaže');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nová súťaž');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava súťaže');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail súťaže');
        $crud->setDefaultSort(['competitionStart' => 'DESC']);

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Názov');

        yield AssociationField::new('competitionType', 'Typ súťaže')
            ->setFormTypeOption('choice_label', 'name')
            ->hideWhenUpdating();

        yield DateTimeField::new('competitionStart', 'Začiatok súťaže')
            ->setFormat('yyyy-MM-dd HH:mm')
            ->setTimezone('Europe/Bratislava')
            ->renderAsNativeWidget()
            ->setFormTypeOption('model_timezone', 'UTC')
            ->setFormTypeOption('view_timezone', 'Europe/Bratislava')
            ->setFormTypeOption('with_seconds', false);

        yield TextareaField::new('description', 'Popis')
            ->setRequired(false)
            ->hideOnIndex();

        yield TextField::new('location', 'Miesto')
            ->setRequired(false);

        yield TextField::new('mainCategoryName', 'Názov hlavnej kategórie');

        yield TextField::new('organizer', 'Organizátor')
            ->setRequired(false);

        yield ChoiceField::new('status', 'Stav')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('class', CompetitionStatus::class)
            ->setFormTypeOption(
                'choice_label',
                fn (CompetitionStatus $choice): string => $choice->trans($this->translator),
            );

        yield NumberField::new('teamMemberCount', 'Počet členov družstva');

        yield NumberField::new('shootersInRound', 'Počet strelcov v runde');

        yield CodeEditorField::new('targetConfigurationSnapshot', 'Snapshot konfigurácie terčov')
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

        yield CollectionField::new('categories', 'Kategórie');
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
