<?php

declare(strict_types=1);

namespace App\Competition\Results;

use App\Competition\Model\CompetitionStatus;
use App\Competition\Results\Model\Categories;
use App\Competition\Results\Model\Category;
use App\Competition\Results\Model\CategoryType;
use App\Entity\Competition;
use Override;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CategoryProvider implements CategorySluggerInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }

    #[Override]
    public function slugCategoryName(string $name): string
    {
        return $this->slugger->slug($name)->lower()->toString();
    }

    public function allForPublic(Competition $competition): Categories
    {
        $isFinished = $competition->getStatus() === CompetitionStatus::Finished;

        $results = [];
        $results[] = Category::create(
            title: $competition->getMainCategoryName(),
            slugger: $this,
            categoryType: CategoryType::General,
            sortByRank: $isFinished,
        );
        if ($isFinished && $competition->getTeamMemberCount() > 0) {
            $results[] = Category::create(
                title: 'Družstvá',
                slugger: $this,
                categoryType: CategoryType::Teams,
                sortByRank: $isFinished,
            );
        }
        foreach ($competition->getCategories() as $category) {
            $results[] = Category::create(
                title: $category->getName(),
                slugger: $this,
                categoryType: CategoryType::Custom,
                sortByRank: $isFinished,
            );
        }

        return new Categories($results);
    }

    public function allForAdmin(Competition $competition): Categories
    {
        $results = [];
        $results[] = Category::create(
            title: $competition->getMainCategoryName(),
            slugger: $this,
            categoryType: CategoryType::General,
            sortByRank: true,
        );
        if ($competition->getTeamMemberCount() > 0) {
            $results[] = Category::create(
                title: 'Družstvá',
                slugger: $this,
                categoryType: CategoryType::Teams,
                sortByRank: true,
            );
        }
        foreach ($competition->getCategories() as $category) {
            $results[] = Category::create(
                title: $category->getName(),
                slugger: $this,
                categoryType: CategoryType::Custom,
                sortByRank: true,
            );
        }

        return new Categories($results);
    }
}
