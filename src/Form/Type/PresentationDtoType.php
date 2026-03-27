<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Competition;
use App\Entity\CompetitionTeam;
use App\Entity\Shooter;
use App\Form\Dto\PresentationDto;
use App\Repository\CompetitionTeamRepository;
use App\Repository\ShooterRepository;
use Override;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PresentationDtoType extends AbstractType
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $firstName = trim($options['first_name_filter'] ?? '');
        $lastName = trim($options['last_name_filter'] ?? '');
        $teamName = trim($options['team_name_filter'] ?? '');
        $competition = $options['competition'];
        assert($competition instanceof Competition);

        $builder->add('firstName', TextType::class, [
            'label' => 'Meno',
            'required' => false,
        ]);
        $builder->add('lastName', TextType::class, [
            'label' => 'Priezvisko',
            'required' => false,
        ]);

        $builder->add('shooter', EntityType::class, [
            'class' => Shooter::class,
            'label' => 'Strelec',
            'placeholder' => 'Nový strelec',
            'required' => false,
            'attr' => [
                'size' => 5,
            ],
            'query_builder' => function (ShooterRepository $repo) use ($lastName, $firstName) {
                $qb = $repo->createQueryBuilder('s');
                $qb = $qb->orderBy('s.lastName', 'ASC');
                $qb = $qb->addOrderBy('s.firstName', 'ASC');

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
        ]);

        $builder->add('club', TextType::class, [
            'label' => 'Klub / PZ',
            'required' => false,
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
            'expanded' => true,
            'multiple' => true,
            'choices' => $competition->getCategories()->toArray(),
            'choice_label' => 'name',
            'label' => 'Kategórie',
            'required' => false,
        ]);

        if ($competition->getTeamMemberCount() > 1) {
            $builder->add('teamName', TextType::class, [
                'label' => 'Družstvo',
                'required' => false,
            ]);

            $builder->add('competitionTeam', EntityType::class, [
                'class' => CompetitionTeam::class,
                'label' => 'Vyber družstvo',
                'placeholder' => 'Nové družstvo',
                'choice_label' => 'choiceLabel',
                'required' => false,
                'attr' => [
                    'size' => 5,
                ],
                'query_builder' => function (CompetitionTeamRepository $repo) use ($teamName, $competition) {
                    $qb = $repo->createQueryBuilder('t');
                    $qb = $qb->orderBy('t.name', 'ASC');
                    $qb = $qb->andWhere('t.competition = :competition');
                    $qb = $qb->setParameter('competition', $competition);

                    if ($teamName !== '') {
                        $qb = $qb->andWhere('t.name LIKE :teamName');
                        $qb = $qb->setParameter('teamName', '%' . $teamName . '%');
                    }

                    if ($teamName === '') {
                        $qb = $qb->andWhere('1 = 0');
                    }

                    return $qb;
                },
            ]);
        }
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PresentationDto::class,
            'first_name_filter' => null,
            'last_name_filter' => null,
            'team_name_filter' => null,
        ]);
        $resolver->setAllowedTypes('first_name_filter', ['null', 'string']);
        $resolver->setAllowedTypes('last_name_filter', ['null', 'string']);
        $resolver->setAllowedTypes('team_name_filter', ['null', 'string']);
        $resolver->setRequired('competition');
        $resolver->setAllowedTypes('competition', [Competition::class]);
    }
}
