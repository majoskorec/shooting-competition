<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CompetitionEntryStatus: string
{
    case Pending = 'pending';
    case Registered = 'registered';
    case Withdrawn = 'withdrawn';
    case Disqualified = 'disqualified';

    public function toSlovak(): string
    {
        return match ($this) {
            self::Pending => 'Prihlásený',
            self::Registered => 'Registrovaný',
            self::Withdrawn => 'Odsúpený',
            self::Disqualified => 'Diskvalifikovaný',
        };
    }
}
