<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Dto\JuryEntryDeleteDto;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<JuryEntryDeleteDto>
 */
final class JuryEntryDeleteType extends AbstractType
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class);
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JuryEntryDeleteDto::class,
            'method' => Request::METHOD_DELETE,
        ]);
    }
}
