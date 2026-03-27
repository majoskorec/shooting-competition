<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Competition\Results\CategoryProvider;
use App\Competition\Results\Model\Categories;
use App\Competition\Results\Model\Category;
use App\Entity\Competition;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Menu
{
    public ?Competition $competition = null;
    public ?Category $category = null;

    public function __construct(
        private readonly CategoryProvider $categoryProvider,
    ) {
    }

    public function getCategories(): Categories
    {
        if ($this->competition === null) {
            return new Categories([]);
        }

        return $this->categoryProvider->allForPublic($this->competition);
    }
}
