<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Partenaires;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->insertUser($manager);
        $this->insertPartenaire($manager);

    }

    public function insertUser(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'adminpassword'));

        $manager->persist($user);
        $manager->flush();
    }

    public function insertPartenaire(ObjectManager $manager): void {
        $partenaire = new Partenaires();
        $partenaire->setNom('Partenaire');

        $manager->persist($partenaire);
        $manager->flush();
    }
}
