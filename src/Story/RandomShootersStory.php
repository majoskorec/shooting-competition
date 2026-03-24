<?php

declare(strict_types=1);

namespace App\Story;

use App\Tests\Factory\ShooterFactory;
use Override;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'random-shooters')]
final class RandomShootersStory extends Story
{
    #[Override]
    public function build(): void
    {
        ShooterFactory::createMany(50);
    }
}
