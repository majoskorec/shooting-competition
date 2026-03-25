<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Override;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Input extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[Override]
    protected function instantiateForm(): FormInterface
    {
        // TODO: Implement instantiateForm() method.
    }
}
