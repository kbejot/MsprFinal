<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Admin;

class CreateAdminCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // Appel du constructeur parent
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
        $admin = new Admin();
        $admin->setUsername('admin');
        $admin->setEmail('admin@mspr.com');
        $admin->setPassword('MSPR2023');

        // Sauvegardez l'administrateur dans la base de données
        $entityManager = $this->container->get('doctrine')->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        $output->writeln('Admin created successfully.');

        return Command::SUCCESS;
    }
}
