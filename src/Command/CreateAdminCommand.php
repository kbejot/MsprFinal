<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class CreateAdminCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Creates a new admin user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Créez un nouvel administrateur
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@mspr.com');
        
        $hashedPassword = $this->passwordHasher->hashPassword($admin, '');
        $admin->setPassword($hashedPassword);

        // Sauvegardez l'administrateur dans la base de données
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $output->writeln('User created successfully.');

        return Command::SUCCESS;
    }
}
