<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

use InvalidArgumentException;

final readonly class SubResult
{
    public function __construct(
        public string $name,
        public int $result,
        public int $tieBreakPriority,
    ) {
    }

    public static function add(self $first, ?self $second): self
    {
        if ($second === null) {
            return new self(
                name: $first->name,
                result: $first->result,
                tieBreakPriority: $first->tieBreakPriority,
            );
        }

        if ($first->name !== $second->name) {
            throw new InvalidArgumentException(sprintf(
                'Cannot add sub results with different names: %s and %s',
                $first->name,
                $second->name,
            ));
        }

        if ($first->tieBreakPriority !== $second->tieBreakPriority) {
            throw new InvalidArgumentException(sprintf(
                'Cannot add sub results with different tie break priorities: %s and %s',
                $first->tieBreakPriority,
                $second->tieBreakPriority,
            ));
        }

        return new self(
            name: $first->name,
            result: $first->result + $second->result,
            tieBreakPriority: $first->tieBreakPriority,
        );
    }
}
