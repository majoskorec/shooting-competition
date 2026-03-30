<?php

declare(strict_types=1);

namespace App\Competition\Results\Model;

enum CategoryType
{
    case Main;
    case Teams;
    case Custom;
}
