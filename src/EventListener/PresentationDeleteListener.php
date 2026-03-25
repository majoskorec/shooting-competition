<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Controller\Admin\Competition\PresentationController;
use App\Entity\Competitor;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEventListener(event: AfterCrudActionEvent::class)]
final class PresentationDeleteListener
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(AfterCrudActionEvent $event): void
    {
        $entity = $event->getAdminContext()?->getEntity()->getInstance();
        if (!$entity instanceof Competitor) {
            return;
        }

        $fromPresentation = $event->getAdminContext()?->getRequest()->query->get('presentation');
        if ($fromPresentation !== '1') {
            return;
        }

        $competitionId = $entity->getCompetition()->getId();
        $event->setResponse(
            new RedirectResponse(
                $this->urlGenerator->generate(PresentationController::ROUTE_NAME, ['entityId' => $competitionId]),
            ),
        );
    }
}
