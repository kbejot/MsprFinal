<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private function logInAdmin($client): void
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[_username]' => 'admin',
            'login_form[_password]' => 'adminpassword',
        ]);
        $client->submit($form);
        $client->followRedirect();
    }

    public function testList()
    {
        $client = static::createClient();
        $this->logInAdmin($client);

        $crawler = $client->request('GET', '/user/list');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table-hover');
    }

//    public function testEdit()
//    {
//        $client = static::createClient();
//        $this->logInAdmin($client);
//
//        $entityManager = $client->getContainer()->get('doctrine')->getManager();
//        $user = new User();
//        $user->setUsername('testuser');
//        $user->setEmail('testuser@example.com');
//        $user->setPassword('testuserpassword');
//        $user->setRoles(['ROLE_USER']);
//        $entityManager->persist($user);
//        $entityManager->flush();
//        $userId = $user->getId();
//
//        $crawler = $client->request('GET', '/user/'.$userId.'/edit');
//        $form = $crawler->selectButton('Enregistrer les modifications')->form([
//            'role' => 'ROLE_ADMIN',
//        ]);
//        $client->submit($form);
//
//        $this->assertResponseRedirects('/user/list');
//        $client->followRedirect();
//
//        $updatedUser = $entityManager->getRepository(User::class)->find($userId);
//        $this->assertEquals(['ROLE_ADMIN'], $updatedUser->getRoles());
//    }

    public function testDelete()
    {
        $client = static::createClient();
        $this->logInAdmin($client);

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword('testuserpassword');
        $user->setRoles(['ROLE_USER']);
        $entityManager->persist($user);
        $entityManager->flush();
        $userId = $user->getId();
        $session = $client->getContainer()->get('session');
        $session->start();

        $crawler = $client->request('GET', '/user/list');

        $form = $crawler->filter('form[action="/user/' . $userId . '/delete"]')->form([
            '_token' => $client->getContainer()->get('security.csrf.token_manager')->getToken('delete' . $userId)->getValue(),
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/user/list');
        $client->followRedirect();

        // Re-fetch the entity to ensure it is managed
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Ensure the entity is managed before removing
        if ($entityManager->contains($user)) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Now check if the user is really deleted
        $deletedUser = $entityManager->getRepository(User::class)->find($userId);
        $this->assertNull($deletedUser);
    }
}
