<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

enum CategoryType
{
    case General;
    case Teams;
    case Custom;
}
