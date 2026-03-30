<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use RuntimeException;

final class Categories
{
    /**
     * @param array<Category> $categories
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

        return $this->getMainCategory();
    }

    private function getMainCategory(): Category
    {
        foreach ($this->categories as $category) {
            if ($category->categoryType === CategoryType::Main) {
                return $category;
            }
        }

        throw new RuntimeException('Missing main category');
    }
}
