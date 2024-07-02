<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Reseaux;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[Group('controller')]
class ReseauxControllerTest extends WebTestCase
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

    public function testIndex()
    {
        $client = static::createClient();
        $this->logInAdmin($client);
        $crawler = $client->request('GET', '/reseaux');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name=reseaux]');
    }

    public function testSubmitValidData()
    {
        $client = static::createClient();
        $this->logInAdmin($client);
        $crawler = $client->request('GET', '/reseaux');

        $form = $crawler->selectButton('Ajouter le réseau')->form([
            'reseaux[nom]' => 'Valid Network Name',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/reseaux');
        $client->followRedirect();

        // Vérifier que le nouveau réseau est bien ajouté à la liste
        $this->assertSelectorTextContains('.table', 'Valid Network Name');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Authentifiez le client avec un utilisateur ayant le rôle ROLE_ADMIN
        $this->logInAdmin($client);

        // Créer un réseau pour le test
        $reseaux = new Reseaux();
        $reseaux->setNom('testreseaux');
        $entityManager->persist($reseaux);
        $entityManager->flush();

        $reseauxId = $reseaux->getId();

        // Vérifier que le réseau existe
        $this->assertNotNull($entityManager->getRepository(Reseaux::class)->find($reseauxId));

        // Supprimer le réseau
        $client->request('GET', '/reseaux/delete/' . $reseauxId);
        $this->assertResponseRedirects('/reseaux');
        $entityManager->remove($reseaux);
        $entityManager->flush();
        $client->followRedirect();

        $entityManager->clear();

        // Vérifier que le réseau a été supprimé
        $this->assertNull($entityManager->getRepository(Reseaux::class)->find($reseauxId));
    }
}
