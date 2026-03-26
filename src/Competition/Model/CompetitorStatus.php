<?php

declare(strict_types=1);

namespace App\Competition\Model;

use Override;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CompetitorStatus: string implements TranslatableInterface
{
    case Pending = 'pending';
    case Registered = 'registered';
    case Withdrawn = 'withdrawn';
    case Disqualified = 'disqualified';

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        $key = sprintf('CompetitorStatus.%s', $this->name);

        return $translator->trans(
            id: $key,
            locale: $locale,
        );
    }
}
