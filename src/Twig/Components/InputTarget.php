<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Competition\Input\Model\InputTarget as InputTargetModel;
use App\Form\Dto\InputTargetDto;
use App\Form\Type\InputTargetType;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Throwable;

#[AsLiveComponent]
final class InputTarget extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public InputTargetModel $data;

    private ?string $itemWidth = null;
    private ?int $shotsCount = null;
    private ?bool $isValid = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->shouldAutoSubmitForm = true;
    }

    public function getItemWidth(): string
    {
        if ($this->itemWidth === null) {
            $this->itemWidth = (string) (floor(10000 / (count($this->data->targetResult->getHitBreakdown()) + 2.5)) / 100);
        }

        return $this->itemWidth;
    }

    public function getTabIndex(int $index): int
    {
        return 100_000 * $this->data->targetIndex + 1_000 * $this->data->competitorStartNumber + $index;
    }

    public function getShotsCount(): int
    {
        if ($this->shotsCount === null) {
            $this->shotsCount = array_sum($this->data->targetResult->getHitBreakdown());
        }

        return $this->shotsCount;
    }

    public function isValid(): bool
    {
        if ($this->isValid === null) {
            $this->isValid = $this->getShotsCount() === $this->data->targetSnapshot->shotCount;
        }

        return $this->isValid;
    }

    #[PreReRender(priority: -10)]
    public function save(): void
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        try {
            $this->submitForm();
        } catch (Throwable) {
            return;
        }


        $inputTargetDto = $this->getForm()->getData();
        assert($inputTargetDto instanceof InputTargetDto);

        $targetResult = $this->data->targetResult;
        $targetResult->setHitBreakdown($inputTargetDto->points);
        $this->entityManager->flush();
    }

    #[Override]
    protected function instantiateForm(): FormInterface
    {
        $dto = new InputTargetDto();
        $dto->points = $this->data->targetResult->getHitBreakdown();

        return $this->createForm(InputTargetType::class, $dto, [
            'target_snapshot' => $this->data->targetSnapshot,
        ]);
    }
}
