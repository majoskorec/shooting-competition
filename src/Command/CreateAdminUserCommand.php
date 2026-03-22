<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create-admin',
    description: 'Creates an admin user for the back office.',
)]
final class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = mb_strtolower(trim($io->ask('Admin e-mail')));
        $password = trim((string) $io->askHidden('Heslo'));
        $fullName = trim((string) $io->ask('Celé meno'));

        if ($email === '' || $password === '' || $fullName === '') {
            $io->error('Email, password and full name are required.');

            return Command::INVALID;
        }

        if ($this->userRepository->findOneBy(['email' => $email]) !== null) {
            $io->error(sprintf('User "%s" already exists.', $email));

            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Admin user "%s" was created.', $email));

        return Command::SUCCESS;
    }
}
