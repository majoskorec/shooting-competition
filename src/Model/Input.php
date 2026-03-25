<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Competition;

final class Input
{
    /**
     * @param array<int, InputCompetitor> $inputCompetitors
     */
    public function __construct(
        public readonly Competition $competition,
        public readonly array $inputCompetitors,
    ) {
    }
}
