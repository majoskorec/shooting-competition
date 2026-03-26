<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class Category
{
    private function __construct(
        public string $title,
        public string $slug,
        public CategoryType $categoryType,
    ) {
    }

    public static function create(
        string $title,
        SluggerInterface $slugger,
        CategoryType $categoryType,
    ): self {
        return new self(
            $title,
            $slugger->slug($title)->lower()->toString(),
            $categoryType,
        );
    }

    public function isHit(string $text): bool
    {
        return $text === $this->slug || $text === $this->title;
    }
}
