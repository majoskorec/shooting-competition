<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Override]
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    #[Override]
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setEntityLabelInSingular('Používateľ');
        $crud->setEntityLabelInPlural('Používatelia');
        $crud->setPageTitle(Crud::PAGE_INDEX, 'Používatelia');
        $crud->setPageTitle(Crud::PAGE_NEW, 'Nový používateľ');
        $crud->setPageTitle(Crud::PAGE_EDIT, 'Úprava používateľa');
        $crud->setPageTitle(Crud::PAGE_DETAIL, 'Detail používateľa');
        $crud->setDefaultSort(['fullName' => 'ASC']);

        return $crud;
    }

    #[Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield EmailField::new('email', 'E-mail');

        yield TextField::new('fullName', 'Celé meno');

        yield ArrayField::new('roles', 'Role')
            ->onlyOnIndex();

        yield ChoiceField::new('roles', 'Role')
            ->setChoices([
                'Admin' => 'ROLE_ADMIN',
            ])
            ->allowMultipleChoices()
            ->renderExpanded()
            ->hideOnIndex()
            ->hideOnDetail();

        yield TextField::new('password', 'Heslo')
            ->setFormType(PasswordType::class)
            ->onlyOnForms()
            ->setFormTypeOption('mapped', false)
            ->setHelp($pageName === Crud::PAGE_EDIT ? 'Vyplň len ak chceš zmeniť heslo.' : '')
            ->setRequired($pageName === Crud::PAGE_NEW);

        yield ArrayField::new('roles', 'Role')
            ->onlyOnDetail();
    }

    #[Override]
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $plainPassword = $this->getSubmittedPassword();
            if ($plainPassword !== null) {
                $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $plainPassword));
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    #[Override]
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $plainPassword = $this->getSubmittedPassword();
            if ($plainPassword !== null) {
                $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $plainPassword));
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function getSubmittedPassword(): ?string
    {
        $context = $this->getContext();
        if ($context === null) {
            return null;
        }

        $formData = $context->getRequest()->request->all()[$context->getEntity()->getName()] ?? null;
        if (!is_array($formData)) {
            return null;
        }

        $plainPassword = $formData['password'] ?? null;
        if (!is_string($plainPassword)) {
            return null;
        }

        $plainPassword = trim($plainPassword);

        return $plainPassword !== '' ? $plainPassword : null;
    }
}
