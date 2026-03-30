<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Competition\Target\Model\TargetSnapshot;
use App\Form\Dto\InputTargetDto;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

/**
 * @extends AbstractType<InputTargetDto>
 */
final class InputTargetType extends AbstractType implements DataMapperInterface
{
    public const string FIELD_PREFIX = 'point_';

    /**
     * @inheritDoc
     */
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $targetSnapshot = $options['target_snapshot'];
        assert($targetSnapshot instanceof TargetSnapshot);

        foreach ($targetSnapshot->pointsSchema as $point) {
            $builder->add(self::FIELD_PREFIX . $point, TextType::class, [
                'empty_data' => 0,
                'label' => false,
                'required' => false,
            ]);
        }

        $builder->setDataMapper($this);
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InputTargetDto::class,
        ]);
        $resolver->setRequired('target_snapshot');
        $resolver->setAllowedTypes('target_snapshot', [TargetSnapshot::class]);
    }

    /**
     * @inheritDoc
     * @param Traversable<FormInterface<mixed>> $forms
     */
    #[Override]
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }

        if (!$viewData instanceof InputTargetDto) {
            throw new Exception\UnexpectedTypeException($viewData, InputTargetDto::class);
        }

        foreach ($forms as $form) {
            $name = $form->getName();
            $points = (int) str_replace(self::FIELD_PREFIX, '', $name);
            $form->setData($viewData->points[$points] ?? 0);
        }
    }

    /**
     * @inheritDoc
     * @param Traversable<FormInterface<mixed>> $forms
     */
    #[Override]
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        $points = [];
        foreach ($forms as $form) {
            $name = $form->getName();
            $point = (int) str_replace(self::FIELD_PREFIX, '', $name);
            $hits = $form->getData();
            assert(is_string($hits));
            $points[$point] = $hits;
        }

        assert($viewData instanceof InputTargetDto);
        $viewData->points = $points;
    }
}
