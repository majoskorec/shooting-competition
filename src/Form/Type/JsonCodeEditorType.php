<?php

declare(strict_types=1);

namespace App\Form\Type;

use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CodeEditorType;
use JsonException;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;

final class JsonCodeEditorType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            static function (mixed $value): string {
                if ($value === null || $value === []) {
                    return '[]';
                }

                if (!is_array($value)) {
                    throw new TransformationFailedException(sprintf('Expected an array, got "%s".', get_debug_type($value)));
                }

                try {
                    return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    throw new TransformationFailedException('Unable to encode JSON.', 0, $exception);
                }
            },
            static function (mixed $value): array {
                if ($value === null || $value === '') {
                    return [];
                }

                if (!is_string($value)) {
                    throw new TransformationFailedException(sprintf('Expected a JSON string, got "%s".', get_debug_type($value)));
                }

                try {
                    $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    throw new TransformationFailedException('Invalid JSON provided.', 0, $exception);
                }

                if (!is_array($decoded)) {
                    throw new TransformationFailedException('JSON must decode to an array.');
                }

                return $decoded;
            },
        ));
    }

    #[Override]
    public function getParent(): string
    {
        return CodeEditorType::class;
    }
}
