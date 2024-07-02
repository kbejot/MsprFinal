<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Infos;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[Group('controller')]
class InfoControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/info');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name=infos]');
    }

    public function testSubmitValidData()
    {
        $client = static::createClient();
        $this->logInAdmin($client);
        $crawler = $client->request('GET', '/info');

        // Remplir le formulaire avec les données appropriées
        $form = $crawler->selectButton('Envoyer l\'information')->form([
            'infos[MessageInfo]' => 'Valid Information Message',
        ]);

        $client->submit($form);

        // Vérifier la redirection après soumission
        $this->assertResponseRedirects('/info');
        $client->followRedirect();

        // Vérifier que la nouvelle information est bien ajoutée à la liste
        $this->assertSelectorTextContains('.table', 'Valid Information Message');
    }

}
