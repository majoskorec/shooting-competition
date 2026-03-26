<?php

declare(strict_types=1);

namespace App\Competition\Results;

use App\Competition\Results\Model\Categories;
use App\Competition\Results\Model\Category;
use App\Competition\Results\Model\CategoryType;
use App\Entity\Competition;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CategoryProvider
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function getPartialResults(Competition $competition): Category
    {
        return Category::create($competition->getMainCategoryName(), $this->slugger, CategoryType::PartialResults);
    }

    public function all(Competition $competition): Categories
    {
        $results = [];
        $results[] = Category::create($competition->getMainCategoryName(), $this->slugger, CategoryType::General);
        if ($competition->getTeamMemberCount() > 0) {
            $results[] = Category::create('Družstvá', $this->slugger, CategoryType::Teams);
        }
        foreach ($competition->getCategories() as $category) {
            $results[] = Category::create($category->getName(), $this->slugger, CategoryType::Custom);
        }

        return new Categories($results);
    }
}
