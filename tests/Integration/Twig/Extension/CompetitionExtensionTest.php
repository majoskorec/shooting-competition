<?php

declare(strict_types=1);

namespace App\Tests\Integration\Twig\Extension;

use App\Twig\Extension\CompetitionExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function Zenstruck\Foundry\faker;

final class CompetitionExtensionTest extends KernelTestCase
{
    public function testRankIconReturnsHtmlBadgeForPodiumPlaces(): void
    {
        $container = self::getContainer();
        $extension = $container->get(CompetitionExtension::class);

        $firstPlace = $extension->rankIcon(1);
        self::assertSame('1. <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" height="1em" width="1em" style="color: #d4af37;" aria-hidden="true"><path fill="currentColor" d="M4.1 38.2C1.4 34.2 0 29.4 0 24.6C0 11 11 0 24.6 0h109.3c11.2 0 21.7 5.9 27.4 15.5l68.5 114.1c-48.2 6.1-91.3 28.6-123.4 61.9zm503.7 0L405.6 191.5c-32.1-33.3-75.2-55.8-123.4-61.9l68.5-114.1C356.5 5.9 366.9 0 378.1 0h109.3C501 0 512 11 512 24.6c0 4.8-1.4 9.6-4.1 13.6zM80 336a176 176 0 1 1 352 0a176 176 0 1 1-352 0m184.4-94.9c-3.4-7-13.3-7-16.8 0l-22.4 45.4c-1.4 2.8-4 4.7-7 5.1l-50.2 7.3c-7.7 1.1-10.7 10.5-5.2 16l36.3 35.4c2.2 2.2 3.2 5.2 2.7 8.3l-8.6 49.9c-1.3 7.6 6.7 13.5 13.6 9.9l44.8-23.6c2.7-1.4 6-1.4 8.7 0l44.8 23.6c6.9 3.6 14.9-2.2 13.6-9.9l-8.6-49.9c-.5-3 .5-6.1 2.7-8.3l36.3-35.4c5.6-5.4 2.5-14.8-5.2-16l-50.1-7.3c-3-.4-5.7-2.4-7-5.1z"/></svg>', $firstPlace);

        $secondPlace = $extension->rankIcon(2);
        self::assertSame('2. <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" height="1em" width="1em" style="color: #9aa4b2;" aria-hidden="true"><path fill="currentColor" d="M4.1 38.2C1.4 34.2 0 29.4 0 24.6C0 11 11 0 24.6 0h109.3c11.2 0 21.7 5.9 27.4 15.5l68.5 114.1c-48.2 6.1-91.3 28.6-123.4 61.9zm503.7 0L405.6 191.5c-32.1-33.3-75.2-55.8-123.4-61.9l68.5-114.1C356.5 5.9 366.9 0 378.1 0h109.3C501 0 512 11 512 24.6c0 4.8-1.4 9.6-4.1 13.6zM80 336a176 176 0 1 1 352 0a176 176 0 1 1-352 0m184.4-94.9c-3.4-7-13.3-7-16.8 0l-22.4 45.4c-1.4 2.8-4 4.7-7 5.1l-50.2 7.3c-7.7 1.1-10.7 10.5-5.2 16l36.3 35.4c2.2 2.2 3.2 5.2 2.7 8.3l-8.6 49.9c-1.3 7.6 6.7 13.5 13.6 9.9l44.8-23.6c2.7-1.4 6-1.4 8.7 0l44.8 23.6c6.9 3.6 14.9-2.2 13.6-9.9l-8.6-49.9c-.5-3 .5-6.1 2.7-8.3l36.3-35.4c5.6-5.4 2.5-14.8-5.2-16l-50.1-7.3c-3-.4-5.7-2.4-7-5.1z"/></svg>', $secondPlace);

        $thirdPlace = $extension->rankIcon(3);
        self::assertSame('3. <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" height="1em" width="1em" style="color: #b87333;" aria-hidden="true"><path fill="currentColor" d="M4.1 38.2C1.4 34.2 0 29.4 0 24.6C0 11 11 0 24.6 0h109.3c11.2 0 21.7 5.9 27.4 15.5l68.5 114.1c-48.2 6.1-91.3 28.6-123.4 61.9zm503.7 0L405.6 191.5c-32.1-33.3-75.2-55.8-123.4-61.9l68.5-114.1C356.5 5.9 366.9 0 378.1 0h109.3C501 0 512 11 512 24.6c0 4.8-1.4 9.6-4.1 13.6zM80 336a176 176 0 1 1 352 0a176 176 0 1 1-352 0m184.4-94.9c-3.4-7-13.3-7-16.8 0l-22.4 45.4c-1.4 2.8-4 4.7-7 5.1l-50.2 7.3c-7.7 1.1-10.7 10.5-5.2 16l36.3 35.4c2.2 2.2 3.2 5.2 2.7 8.3l-8.6 49.9c-1.3 7.6 6.7 13.5 13.6 9.9l44.8-23.6c2.7-1.4 6-1.4 8.7 0l44.8 23.6c6.9 3.6 14.9-2.2 13.6-9.9l-8.6-49.9c-.5-3 .5-6.1 2.7-8.3l36.3-35.4c5.6-5.4 2.5-14.8-5.2-16l-50.1-7.3c-3-.4-5.7-2.4-7-5.1z"/></svg>', $thirdPlace);
    }

    public function testRankIconReturnsPlainRankForNonPodiumPlace(): void
    {
        $container = self::getContainer();
        $extension = $container->get(CompetitionExtension::class);

        $rank = faker()->numberBetween(4, 999);

        self::assertSame((string) $rank, $extension->rankIcon($rank));
    }
}
