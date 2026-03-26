<?php

declare(strict_types=1);

namespace App\Doctrine\Mapping;

use App\Competition\Target\Model\TargetSnapshot;
use Override;

final class TargetSnapshotsType extends JsonObjectType
{
    public const string TYPE = TargetSnapshot::class . '[]';

    #[Override]
    protected function getSerializationType(): string
    {
        return self::TYPE;
    }
}
