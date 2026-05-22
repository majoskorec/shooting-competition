<?php

declare(strict_types=1);

namespace App\Competition\Category;

use App\Entity\Competition;
use App\Entity\CompetitionCategory;
use App\Entity\CompetitionCategoryRule;
use App\Entity\ShooterGender;

final class CompetitionCategoryRuleEvaluator
{
    public function isApplicable(
        CompetitionCategory $category,
        Competition $competition,
        ?int $birthYear,
        ?ShooterGender $gender,
    ): bool {
        $rule = $category->getRule();
        if ($rule === null) {
            return true;
        }

        if ($birthYear === null || $gender === null) {
            return false;
        }

        $competitionYear = (int) $competition->getCompetitionStart()->format('Y');
        $ageInCompetitionYear = $competitionYear - $birthYear;

        return match ($rule) {
            CompetitionCategoryRule::Juniors => $ageInCompetitionYear <= 20,
            CompetitionCategoryRule::MenSeniors => $gender === ShooterGender::Male
                && $ageInCompetitionYear >= 21
                && $ageInCompetitionYear <= 60,
            CompetitionCategoryRule::MenVeterans => $gender === ShooterGender::Male && $ageInCompetitionYear >= 61,
            CompetitionCategoryRule::Women => $gender === ShooterGender::Female && $ageInCompetitionYear >= 21,
        };
    }
}
