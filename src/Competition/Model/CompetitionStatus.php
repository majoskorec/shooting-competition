<?php

declare(strict_types=1);

namespace App\Competition\Model;

use Override;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CompetitionStatus: string implements TranslatableInterface
{
    case Draft = 'draft';
    case Presentation = 'presentation';
    case InProgress = 'in_progress';
    case ReadyForClosure = 'ready_for_closure';
    case Finished = 'finished';
    case Closed = 'closed';

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        $key = sprintf('CompetitionStatus.%s', $this->name);

        return $translator->trans(
            id: $key,
            locale: $locale,
        );
    }

    public function backgroundColor(): string
    {
        return match ($this) {
            self::Presentation, self::InProgress, self::ReadyForClosure => 'text-bg-warning',
            default => 'text-bg-success',
        };
    }

    public function isPublished(): bool
    {
        return match ($this) {
            self::Presentation, self::InProgress, self::ReadyForClosure, self::Finished => true,
            default => false,
        };
    }
}
