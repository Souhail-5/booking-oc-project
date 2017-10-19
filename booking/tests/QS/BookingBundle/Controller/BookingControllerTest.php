<?php

namespace Tests\QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Visite du Musée du Louvre', $crawler->filter('#event-wrap')->text());
    }

    public function testGuichet()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/guichet/visite-musee-louvre');
        $form = $crawler->selectButton('Valider mon choix')->form();
        $form['qs_bookingbundle_order[email]'] = 'fauxemail';
        $client->submit($form);
        $this->assertContains('form-errors', $client->getResponse()->getContent());
    }
}
