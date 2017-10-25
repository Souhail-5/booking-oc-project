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
}
