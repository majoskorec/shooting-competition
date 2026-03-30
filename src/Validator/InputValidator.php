<?php

declare(strict_types=1);

namespace App\Validator;

use App\Competition\Input\Model\Input as InputModel;
use App\Competition\Input\Model\InputTarget;
use App\Competition\Model\CompetitionStatus;
use App\Entity\Shooter;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class InputValidator extends ConstraintValidator
{
    private const array FINAL_STATUSES = [
        CompetitionStatus::ReadyForClosure,
        CompetitionStatus::Finished,
    ];

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
                $this->validateInputTargetShotCount(
                    $constraint,
                    $inputTarget,
                    $value->competition->getStatus(),
                    $input->competitor->getShooter(),
                );
            }
        }
    }

    private function validateInputTargetShotCount(
        Input $constraint,
        InputTarget $inputTarget,
        CompetitionStatus $status,
        Shooter $shooter,
    ): void {
        $expectedShotCount = $inputTarget->targetSnapshot->shotCount;
        $shotCount = array_sum($inputTarget->targetResult->getHitBreakdown());
        if ($shotCount === $expectedShotCount) {
            return;
        }

        if ($shotCount === 0) {
            if (in_array($status, self::FINAL_STATUSES, true)) {
                $this->context->buildViolation($constraint->lessShots)
                    ->setParameter('{{ shooter }}', $shooter->getFullName())
                    ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
                    ->setCause('danger') // toto je hack
                    ->addViolation();
            }

            return;
        }

        if ($shotCount < $expectedShotCount) {
            $this->context->buildViolation($constraint->lessShots)
                ->setParameter('{{ shooter }}', $shooter->getFullName())
                ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
                ->setCause('warning') // toto je hack
                ->addViolation();

            return;
        }

        $this->context->buildViolation($constraint->moreShots)
            ->setParameter('{{ shooter }}', $shooter->getFullName())
            ->setParameter('{{ targetName }}', $inputTarget->targetSnapshot->name)
            ->setCause('danger') // toto je hack
            ->addViolation();
    }
}
