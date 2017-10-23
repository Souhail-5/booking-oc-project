<?php

namespace Tests\QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Accueil', $crawler->filter('head title')->text());
    }

    public function testBilletterie()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Billetterie', $crawler->filter('head title')->text());
    }

    public function testGuichetInvalidEmail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/guichet/visite-musee-louvre');
        $form = $crawler->selectButton('Valider mon choix')->form();
        $form['qs_bookingbundle_order[email]'] = 'fauxemail';
        $client->submit($form);
        $this->assertContains('form-errors', $client->getResponse()->getContent());
    }

    public function testConfirmationInvalidOrder()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/confirmation/x');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertContains('Oups', $client->getResponse()->getContent());
    }
}
