<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Competition\Model\CompetitorStatus;
use App\Controller\Admin\Competition\PresentationController;
use App\Entity\Competitor;
use App\Repository\CompetitionCategoryRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CompetitorCrudController extends AbstractCrudController
{
    private const string SAVE_AND_GO_TO_PRESENTATION = 'saveAndGoToPresentation';

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
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('choice_label', 'name')
            ->setFormTypeOption('query_builder', function (CompetitionCategoryRepository $repository): QueryBuilder {
                $queryBuilder = $repository->createQueryBuilder('category')
                    ->orderBy('category.name', 'ASC');

                $competitor = $this->getContext()?->getEntity()?->getInstance();
                if (!$competitor instanceof Competitor) {
                    return $queryBuilder;
                }

                return $queryBuilder
                    ->andWhere('category.competition = :competition')
                    ->setParameter('competition', $competitor->getCompetition());
            });
    }

    #[Override]
    public function configureActions(Actions $actions): Actions
    {
        $actions->add(
            Crud::PAGE_EDIT,
            Action::new(self::SAVE_AND_GO_TO_PRESENTATION, 'Uložiť a ísť na prezentáciu')
                ->renderAsButton()
                ->setHtmlAttributes([
                    'name' => 'ea[newForm][btn]',
                    'value' => self::SAVE_AND_GO_TO_PRESENTATION,
                ])
                // toto sice ea vyzaduje ale ak som spravne pochopil nikde sa to napouzije
                ->linkToRoute(
                    PresentationController::ROUTE_NAME,
                    static fn  (Competitor $competitor): array => [
                        'entityId' => $competitor->getId(),
                    ],
                )
                ->asPrimaryAction(),
        );

        return $actions;
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

    #[Override]
    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {
        $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'] ?? null;
        if (self::SAVE_AND_GO_TO_PRESENTATION !== $submitButtonName) {
            return parent::getRedirectResponseAfterSave($context, $action);
        }

        $competitor = $context->getEntity()->getInstance();
        if (!$competitor instanceof Competitor) {
            return parent::getRedirectResponseAfterSave($context, $action);
        }

        return $this->redirectToRoute(PresentationController::ROUTE_NAME, [
            'entityId' => $competitor->getCompetition()->getId(),
        ]);
    }
}
