<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Competition\Category\CompetitionCategoryRuleEvaluator;
use App\Entity\Competition;
use App\Entity\CompetitionCategory;
use App\Entity\CompetitionTeam;
use App\Entity\Shooter;
use App\Entity\ShooterGender;
use App\Form\Dto\PresentationDto;
use App\Repository\CompetitionTeamRepository;
use App\Repository\CompetitorRepository;
use App\Repository\ShooterRepository;
use Doctrine\DBAL\Types\Types;
use Override;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<PresentationDto>
 */
final class PresentationDtoType extends AbstractType
{
    public function __construct(
        private readonly CompetitionCategoryRuleEvaluator $competitionCategoryRuleEvaluator,
        private readonly CompetitorRepository $competitorRepository,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $firstName = $options['first_name_filter'] ?? '';
        assert(is_string($firstName));
        $firstName = trim($firstName);
        $lastName = $options['last_name_filter'] ?? '';
        assert(is_string($lastName));
        $lastName = trim($lastName);
        $teamName = $options['team_name_filter'] ?? '';
        assert(is_string($teamName));
        $teamName = trim($teamName);
        $birthYear = $options['birth_year'] ?? null;
        assert(is_int($birthYear) || $birthYear === null);
        /** @var array<string> $categoryIds */
        $categoryIds = $options['category_ids'] ?? [];
        $gender = $options['gender'] ?? null;
        assert($gender instanceof ShooterGender || $gender === null);
        $competition = $options['competition'];
        assert($competition instanceof Competition);
        $categoryChoices = array_values($competition->getCategories()->toArray());
        $registeredShooterIds = array_fill_keys(
            $this->competitorRepository->findRegisteredShooterIdsForCompetition($competition),
            true,
        );

        $builder->add('firstName', TextType::class, [
            'label' => 'Meno',
            'required' => false,
        ]);
        $builder->add('lastName', TextType::class, [
            'label' => 'Priezvisko',
            'required' => false,
        ]);

        $builder->add('shooter', EntityType::class, [
            'choice_attr' => static function (Shooter $shooter) use ($registeredShooterIds): array {
                $shooterId = $shooter->getId();
                if ($shooterId !== null && isset($registeredShooterIds[$shooterId])) {
                    return ['disabled' => 'disabled'];
                }

                return [];
            },
            'choice_label' => static function (Shooter $shooter) use ($registeredShooterIds): string {
                $label = sprintf(
                    '%s (%d, %s)',
                    $shooter->getFullName(),
                    $shooter->getBirthYear(),
                    $shooter->getGender()->label(),
                );
                $shooterId = $shooter->getId();
                if ($shooterId !== null && isset($registeredShooterIds[$shooterId])) {
                    return sprintf('%s (registrovaný)', $label);
                }

                return $label;
            },
            'class' => Shooter::class,
            'expanded' => true,
            'label' => 'Strelec',
            'placeholder' => 'Nový strelec',
            'query_builder' => static function (ShooterRepository $repo) use ($lastName, $firstName) {
                $qb = $repo->createQueryBuilder('s');
                $qb = $qb->orderBy('s.lastName', 'ASC');
                $qb = $qb->addOrderBy('s.firstName', 'ASC');
                $qb = $qb->setMaxResults(5);
                if ($firstName !== '') {
                    $qb = $qb->andWhere('s.firstName LIKE :firstName');
                    $qb = $qb->setParameter('firstName', '%' . $firstName . '%');
                }
                if ($lastName !== '') {
                    $qb = $qb->andWhere('s.lastName LIKE :lastName');
                    $qb = $qb->setParameter('lastName', '%' . $lastName . '%');
                }
                if ($firstName === '' && $lastName === '') {
                    $qb = $qb->andWhere('1 = 0');
                }

                return $qb;
            },
            'required' => false,
        ]);

        $builder->add('club', TextType::class, [
            'label' => 'Klub / PZ',
            'required' => false,
        ]);
        $builder->add('birthYear', IntegerType::class, [
            'label' => 'Rok narodenia',
            'required' => true,
        ]);
        $builder->add('gender', EnumType::class, [
            'choice_label' => static fn (ShooterGender $choice): string => $choice->label(),
            'class' => ShooterGender::class,
            'expanded' => true,
            'label' => 'Pohlavie',
            'required' => true,
        ]);
        $builder->add('email', EmailType::class, [
            'label' => 'E-mail',
            'required' => false,
        ]);
        $builder->add('sharedWeaponCode', TextType::class, [
            'label' => 'Zdieľaná zbraň',
            'required' => false,
        ]);
        $builder->add('categories', ChoiceType::class, [
            'choices' => $categoryChoices,
            'choice_attr' => function (
                CompetitionCategory $category,
                mixed $key,
                mixed $value,
            ) use (
                $birthYear,
                $categoryIds,
                $competition,
                $gender,
            ): array {
                $warning = $this->categoryWarning(
                    $category,
                    $competition,
                    $birthYear,
                    $gender,
                    $categoryIds,
                    $value,
                );

                return $warning === null
                    ? []
                    : ['data-warning' => $warning];
            },
            'choice_label' => 'name',
            'expanded' => true,
            'label' => 'Kategórie',
            'multiple' => true,
            'required' => false,
        ]);

        $teamMemberCount = $competition->getTeamMemberCount();
        if ($teamMemberCount <= 1) {
            return;
        }

        $builder->add('teamName', TextType::class, [
            'label' => 'Družstvo',
            'required' => false,
        ]);

        $builder->add('competitionTeam', EntityType::class, [
            'attr' => [
                'size' => 5,
            ],
            'choice_attr' => static fn (CompetitionTeam $competitionTeam): array
                => $competitionTeam->getMembers()->count() >= $teamMemberCount
                    ? ['disabled' => 'disabled']
                    : [],
            'choice_label' => 'presentationChoiceLabel',
            'class' => CompetitionTeam::class,
            'expanded' => true,
            'label' => 'Vyber družstvo',
            'placeholder' => 'Nové družstvo',
            'query_builder' => static function (CompetitionTeamRepository $repo) use ($teamName, $competition) {
                $qb = $repo->createQueryBuilder('t');
                $qb = $qb->orderBy('t.name', 'ASC');
                $qb = $qb->andWhere('t.competition = :competition');
                $qb = $qb->setParameter('competition', $competition->getId(), Types::INTEGER);
                $qb = $qb->setMaxResults(5);
                if ($teamName !== '') {
                    $qb = $qb->andWhere('t.name LIKE :teamName');
                    $qb = $qb->setParameter('teamName', '%' . $teamName . '%');
                }
                if ($teamName === '') {
                    $qb = $qb->andWhere('1 = 0');
                }

                return $qb;
            },
            'required' => false,
        ]);
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'birth_year' => null,
            'category_ids' => [],
            'data_class' => PresentationDto::class,
            'first_name_filter' => null,
            'gender' => null,
            'last_name_filter' => null,
            'team_name_filter' => null,
        ]);
        $resolver->setAllowedTypes('birth_year', ['int', 'null']);
        $resolver->setAllowedTypes('category_ids', ['array']);
        $resolver->setAllowedTypes('first_name_filter', ['null', 'string']);
        $resolver->setAllowedTypes('gender', ['null', ShooterGender::class]);
        $resolver->setAllowedTypes('last_name_filter', ['null', 'string']);
        $resolver->setAllowedTypes('team_name_filter', ['null', 'string']);
        $resolver->setRequired('competition');
        $resolver->setAllowedTypes('competition', [Competition::class]);
    }

    /**
     * @param array<string> $categoryIds
     */
    private function categoryWarning(
        CompetitionCategory $category,
        Competition $competition,
        ?int $birthYear,
        ?ShooterGender $gender,
        array $categoryIds,
        mixed $value,
    ): ?string {
        if ($birthYear === null || $gender === null || $category->getRule() === null) {
            return null;
        }

        if (!is_string($value) && !is_int($value)) {
            return null;
        }

        $isApplicable = $this->competitionCategoryRuleEvaluator->isApplicable(
            $category,
            $competition,
            $birthYear,
            $gender,
        );
        $isSelected = in_array((string) $value, $categoryIds, true);
        if ($isApplicable === $isSelected) {
            return null;
        }

        return $isApplicable
            ? 'Strelec spĺňa podmienky tejto kategórie, ale nemá ju vybranú.'
            : 'Strelec nespĺňa podmienky tejto kategórie.';
    }
}
