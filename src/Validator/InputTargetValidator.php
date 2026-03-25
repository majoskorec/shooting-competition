<?php

declare(strict_types=1);

namespace App\Validator;

use App\Form\Dto\InputTargetDto;
use App\Form\Type\InputTargetType;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class InputTargetValidator extends ConstraintValidator
{
    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof InputTarget) {
            throw new UnexpectedTypeException($constraint, InputTarget::class);
        }

        if (!$value instanceof InputTargetDto) {
            throw new UnexpectedValueException($value, InputTargetDto::class);
        }

        foreach ($value->points as $points => $hits) {
            $intHits = (int) $hits;
            if ((string) $intHits === $hits && $hits >= $intHits) {
                continue;
            }

            $this->context->buildViolation($constraint->invalidValue)
                ->atPath(InputTargetType::FIELD_PREFIX . $points)
                ->addViolation();
        }
    }
}
