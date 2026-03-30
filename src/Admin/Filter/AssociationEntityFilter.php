<?php

declare(strict_types=1);

namespace App\Admin\Filter;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\EntityFilterType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\ComparisonType;
use Override;
use Symfony\Contracts\Translation\TranslatableInterface;

final class AssociationEntityFilter implements FilterInterface
{
    use FilterTrait;

    /**
     * @param class-string $entityClass
     */
    public static function new(
        string $entityClass,
        string $propertyName,
        TranslatableInterface|string|null $label = null,
        string $choiceLabel = 'id',
    ): self {
        return new self()
            ->setFilterFqcn(self::class)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(EntityFilterType::class)
            ->setFormTypeOption('value_type_options.class', $entityClass)
            ->setFormTypeOption('value_type_options.choice_label', $choiceLabel);
    }

    #[Override]
    public function apply(
        QueryBuilder $queryBuilder,
        FilterDataDto $filterDataDto,
        ?FieldDto $fieldDto,
        EntityDto $entityDto,
    ): void {
        $comparison = $filterDataDto->getComparison();
        $parameterName = $filterDataDto->getParameterName();
        $value = $filterDataDto->getValue();
        $associationPath = $filterDataDto->getProperty();

        $currentAlias = $filterDataDto->getEntityAlias();
        foreach (explode('.', $associationPath) as $index => $segment) {
            $currentAlias = sprintf('ea_%s_%d', $parameterName, $index);
            $path = sprintf(
                '%s.%s',
                $index === 0
                    ? $filterDataDto->getEntityAlias()
                    : sprintf('ea_%s_%d', $parameterName, $index - 1),
                $segment,
            );
            $queryBuilder->leftJoin(
                $path,
                $currentAlias,
            );
        }

        if ($value === null) {
            $queryBuilder->andWhere(sprintf('%s %s', $currentAlias, $comparison));

            return;
        }

        $orX = new Orx();
        $orX->add(sprintf('%s %s (:%s)', $currentAlias, $comparison, $parameterName));
        if (ComparisonType::NEQ === $comparison) {
            $orX->add(sprintf('%s IS NULL', $currentAlias));
        }

        $queryBuilder->andWhere($orX)
            ->setParameter($parameterName, $value);
    }
}
