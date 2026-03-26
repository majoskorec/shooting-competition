<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use RuntimeException;

final class Categories
{
    /**
     * @param list<Category> $categories
     */
    public function __construct(
        public array $categories,
    ) {
    }

    public function getByText(string $text): Category
    {
        foreach ($this->categories as $category) {
            if ($category->isHit($text)) {
                return $category;
            }
        }

        foreach ($this->categories as $category) {
            if ($category->categoryType === CategoryType::General) {
                return $category;
            }
        }

        throw new RuntimeException(sprintf('Category with text "%s" not found', $text));
    }
}
