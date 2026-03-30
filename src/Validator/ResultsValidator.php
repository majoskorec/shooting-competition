<?php

declare(strict_types=1);

namespace App\Validator;

use App\Competition\Results\Model\CategoryType;
use App\Competition\Results\Model\Results as ResultsModel;
use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ResultsValidator extends ConstraintValidator
{
    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Results) {
            throw new UnexpectedTypeException($constraint, Results::class);
        }

        if (!$value instanceof ResultsModel) {
            throw new UnexpectedValueException($value, ResultsModel::class);
        }

        $this->validateSameResults($constraint, $value);
    }

    private function validateSameResults(Results $constraint, ResultsModel $value): void
    {
        if ($value->category->categoryType === CategoryType::Teams) {
            return;
        }

        $results = $this->prepareSameResultData($value);
        foreach ($results as $ranks) {
            if (count($ranks) < 2) {
                continue;
            }

            $this->buildSameResultViolation($constraint, $ranks);
        }
    }

    /**
     * @param non-empty-array<int> $ranks
     */
    private function buildSameResultViolation(Results $constraint, array $ranks): void
    {
        sort($ranks);
        $highestRank = min($ranks);
        $cause = $highestRank <= 3 ? 'danger' : 'warning';
        $rankLabel = implode(', ', $ranks);

        $this->context->buildViolation($constraint->sameResult)
            ->setParameter('{{ rank }}', $rankLabel)
            ->setCause($cause) // toto je hack
            ->addViolation();
    }

    /**
     * @return array<int, array<int>>
     */
    private function prepareSameResultData(ResultsModel $value): array
    {
        $results = [];
        foreach ($value->competitorsResultsWithRank as $competitorResult) {
            if ($competitorResult->rank > 6) {
                continue;
            }

            $results[$competitorResult->competitorResult->finalResult][] = $competitorResult->rank;
        }

        return $results;
    }
}
