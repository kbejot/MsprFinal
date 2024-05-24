<?php

namespace App\Tests\Controller\GestionAdmin;

use App\Entity\Partenaires;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PartControllerTest extends WebTestCase
{
    private function logInAdmin($client)
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'login_form[_username]' => 'admin',
            'login_form[_password]' => 'adminpassword',
        ]);
        $client->submit($form);
        $client->followRedirect();
    }

    public function testIndex()
    {
        $client = static::createClient();
        $this->logInAdmin($client);
        $crawler = $client->request('GET', '/part');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name=part]');
    }

    public function testSubmitValidData()
    {
        $client = static::createClient();
        $this->logInAdmin($client);
        $crawler = $client->request('GET', '/part');

        $form = $crawler->selectButton('Ajouter le réseau')->form([
            'part[nom]' => 'Valid Partner Name',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/part');
        $client->followRedirect();

        // Vérifier que le nouveau partenaire est bien ajouté à la liste
        $this->assertSelectorTextContains('.table', 'Valid Partner Name');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Authentifiez le client avec un utilisateur ayant le rôle ROLE_ADMIN
        $this->logInAdmin($client);

        // Créer un partenaire pour le test
        $partenaire = new Partenaires();
        $partenaire->setNom('testpartenaire');
        $entityManager->persist($partenaire);
        $entityManager->flush();

        $partenaireId = $partenaire->getId();

        // Vérifier que le partenaire existe
        $this->assertNotNull($entityManager->getRepository(Partenaires::class)->find($partenaireId));

        // Supprimer le partenaire
        $client->request('GET', '/part/delete/' . $partenaireId);
        $this->assertResponseRedirects('/part');
        $entityManager->remove($partenaire);
        $entityManager->flush();
        $client->followRedirect();

        // Vérifier que le partenaire a été supprimé
        $this->assertNull($entityManager->getRepository(Partenaires::class)->find($partenaireId));
    }
}
