<?php

declare(strict_types=1);

namespace App\Competition\Results;

use App\Competition\Results\Model\Category;
use App\Competition\Results\Model\CategoryType;
use App\Competition\Results\Model\CompetitorResult;
use App\Competition\Results\Model\CompetitorResultWithRank;
use App\Competition\Results\Model\CompetitorSubResults;
use App\Competition\Results\Model\Results;
use App\Competition\Results\Model\SubResult;
use App\Entity\Competition;
use App\Entity\Competitor;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * @psalm-type TargetTieBreakPriority = array<string, int>
 */
final class ResultsFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function create(Competition $competition, Category $category): Results
    {
        $competitors = $this->entityManager->getRepository(Competitor::class)
            ->findForCompetitionAndCategory($competition, $category);
//        $competitors = [$competitors[0], $competitors[1], $competitors[2], $competitors[3]];

        $targetPriority = $this->createTargetTieBreakPriority($competition);
        $competitorSubResults = $this->createCompetitorSubResults($competitors, $targetPriority);
        $competitorResult = $this->createCompetitorResults($competitorSubResults, $category);
        $competitorResultWithRank = $this->createCompetitorResultWithRank($competitorResult);

        return new Results(
            competition: $competition,
            competitorsResultsWithRank: $competitorResultWithRank,
            category: $category,
        );
    }

    /**
     * @param array<CompetitorResult> $competitorResult
     * @return array<CompetitorResultWithRank>
     */
    private function createCompetitorResultWithRank(array $competitorResult): array
    {
        usort(
            $competitorResult,
            static fn (CompetitorResult $left, CompetitorResult $right): int => $right->compare($left),
        );

        $result = [];
        foreach ($competitorResult as $index => $competitorResultItem) {
            $result[] = new CompetitorResultWithRank(
                competitorResult: $competitorResultItem,
                rank: $index + 1,
            );
        }

        return $result;
    }

    /**
     * @return TargetTieBreakPriority
     */
    private function createTargetTieBreakPriority(Competition $competition): array
    {
        $result = [];
        foreach ($competition->getTargetConfigurationSnapshot() as $targetConfigurationSnapshot) {
            $result[$targetConfigurationSnapshot->name] = $targetConfigurationSnapshot->tieBreakPriority;
        }

        return $result;
    }

    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     * @return array<CompetitorResult>
     */
    private function createCompetitorResults(array $competitorSubResults, Category $category): array
    {
        return match ($category->categoryType) {
            CategoryType::Teams => $this->createTeamCompetitorResult($competitorSubResults),
            default => $this->createIndividualCompetitorResult($competitorSubResults),
        };
    }

    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     * @return array<CompetitorResult>
     */
    private function createTeamCompetitorResult(array $competitorSubResults): array
    {
        $result = [];
        foreach ($competitorSubResults as $competitorSubResult) {
            $team = $competitorSubResult->competitor->getCompetitionTeam()
                ?? throw new RuntimeException('Competitor team not found ' . $competitorSubResult->competitor->getId());
            $competitorResult = $result[$team->getName()] ?? null;
            if ($competitorResult === null) {
                $result[$team->getName()] = new CompetitorResult(
                    name: $team->getName(),
                    finalResult: $competitorSubResult->total,
                    competitorSubResults: [$competitorSubResult],
                );

                continue;
            }

            $result[$team->getName()] = CompetitorResult::addCompetitorSubResults(
                competitorResult: $competitorResult,
                competitorSubResults: $competitorSubResult,
            );
        }

        return $result;
    }

    /**
     * @param array<CompetitorSubResults> $competitorSubResults
     * @return array<CompetitorResult>
     */
    private function createIndividualCompetitorResult(array $competitorSubResults): array
    {
        $result = [];
        foreach ($competitorSubResults as $competitorSubResult) {
            $result[] = new CompetitorResult(
                name: $competitorSubResult->competitor->getShooter()->getFullName(),
                finalResult: $competitorSubResult->total,
                competitorSubResults: [$competitorSubResult],
            );
        }

        return $result;
    }

    /**
     * @param array<Competitor> $competitors
     * @param TargetTieBreakPriority $targetTieBreakPriority
     * @return array<CompetitorSubResults>
     */
    private function createCompetitorSubResults(array $competitors, array $targetTieBreakPriority): array
    {
        $result = [];
        foreach ($competitors as $competitor) {
            $result[] = new CompetitorSubResults(
                competitor: $competitor,
                subResults: $this->createSubResults($competitor, $targetTieBreakPriority),
            );
        }

        return $result;
    }

    /**
     * @param TargetTieBreakPriority $targetTieBreakPriority
     * @return array<SubResult>
     */
    private function createSubResults(Competitor $competitor, array $targetTieBreakPriority): array
    {
        $result = [];
        foreach ($competitor->getTargetResults() as $targetResult) {
            $targetName = $targetResult->getTargetName();
            $tieBreakPriority = $targetTieBreakPriority[$targetName]
                ?? throw new RuntimeException('Target tie break priority not found for target ' . $targetName);

            $result[$targetName] = new SubResult(
                name: $targetName,
                result: $targetResult->getSubtotal(),
                tieBreakPriority: $tieBreakPriority,
            );
        }

        return $result;
    }
}
