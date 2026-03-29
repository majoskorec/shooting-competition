<?php

declare(strict_types=1);

namespace App\Competition\Input\Model;

use App\Entity\Competition;

#[\App\Validator\Input]
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
