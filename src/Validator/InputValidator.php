<?php

declare(strict_types=1);

namespace App\Validator;

use App\Competition\Input\Model\Input as InputModel;
use App\Competition\Model\CompetitionStatus;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class InputValidator extends ConstraintValidator
{
    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Input) {
            throw new UnexpectedTypeException($constraint, Input::class);
        }

        if (!$value instanceof InputModel) {
            throw new UnexpectedValueException($value, InputModel::class);
        }

        $this->validateShotCount($constraint, $value);
    }

    private function validateShotCount(Input $constraint, InputModel $value): void
    {
        foreach ($value->inputCompetitors as $input) {
            foreach ($input->inputTargets as $inputTarget) {
                $expectedShotCount = $inputTarget->targetSnapshot->shotCount;
                $shotCount = array_sum($inputTarget->targetResult->getHitBreakdown());
                if (
                    $shotCount === 0
                    && in_array($value->competition->getStatus(), [
                        CompetitionStatus::ReadyForClosure,
                        CompetitionStatus::Finished,
                    ], true)
                ) {
                    $this->context->buildViolation($constraint->lessShots)
                        ->setParameter('{{ shooter }}', $input->competitor->getShooter()->getFullName())
                        ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
                        ->setCause('danger') // toto je hack
                        ->addViolation();
                }

                if ($shotCount !== 0 && $shotCount < $expectedShotCount) {
                    $this->context->buildViolation($constraint->lessShots)
                        ->setParameter('{{ shooter }}', $input->competitor->getShooter()->getFullName())
                        ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
                        ->setCause('warning') // toto je hack
                        ->addViolation();
                }

                if ($shotCount === $expectedShotCount) {
                    continue;
                }

                $this->context->buildViolation($constraint->moreShots)
                    ->setParameter('{{ shooter }}', $input->competitor->getShooter()->getFullName())
                    ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
                    ->setCause('danger') // toto je hack
                    ->addViolation();
            }
        }
    }
}
