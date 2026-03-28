<?php

declare(strict_types=1);

namespace App\Competition\Results;

interface CategorySluggerInterface
{
    public function slugCategoryName(string $name): string;
}
